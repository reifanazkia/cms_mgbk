<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // Step 1: tampilkan form buyer + alamat
    public function step1(Product $product)
    {
        return view('checkout.step1', compact('product'));
    }

    public function processStep1(Request $request)
    {
        $request->validate([
            'product_id'     => 'required|exists:products,id',
            'customer_name'  => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'province'       => 'required|string|max:100',
            'city'           => 'required|string|max:100',
        ]);

        // simpan ke session
        session([
            'checkout.product_id'    => $request->product_id,
            'checkout.customer_name' => $request->customer_name,
            'checkout.email'         => $request->email,
            'checkout.phone'         => $request->phone,
            'checkout.address'       => $request->address,
            'checkout.province'      => $request->province,
            'checkout.city'          => $request->city,
        ]);

        return redirect()->route('checkout.step2');
    }

    // Step 2: pilih payment + summary
    public function step2()
    {
        $data = session('checkout');
        if (!isset($data['product_id'])) {
            return redirect()->route('checkout.step1', ['product' => 0])->with('error', 'Produk tidak dipilih.');
        }

        $product = Product::findOrFail($data['product_id']);
        return view('checkout.step2', compact('product', 'data'));
    }

    public function processStep2(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:VA,QRIS,COD',
        ]);

        $data = session('checkout');
        if (!isset($data['product_id'])) {
            return redirect()->route('checkout.step1', ['product' => 0])->with('error', 'Produk tidak dipilih.');
        }

        $product = Product::findOrFail($data['product_id']);

        // Gunakan harga setelah diskon jika ada
        $price = $product->discounted_price; // discounted price
        $ongkir = 0; // atau hitung otomatis jika diperlukan
        $total = $price + $ongkir;
        $invoice = 'INV-' . now()->format('YmdHis') . '-' . Str::random(4);

        // Membuat order
        $order = Order::create([
            'invoice_number' => $invoice,
            'customer_name'  => $data['customer_name'],
            'email'          => $data['email'],
            'phone'          => $data['phone'],
            'address'        => $data['address'],
            'province'       => $data['province'],
            'city'           => $data['city'],
            'product_id'     => $product->id,
            'price'          => $product->price, // harga sebelum diskon
            'ongkir'         => $ongkir,
            'total'          => $total, // total setelah diskon dan ongkir
            'payment_method' => $request->payment_method,
            'status'         => 'pending',
        ]);

        // Integrasi dengan Duitku jika memilih VA atau QRIS
        if (in_array($request->payment_method, ['VA','QRIS'])) {
            $merchantCode     = env('DUITKU_MERCHANT_CODE');
            $merchantKey      = env('DUITKU_API_KEY');
            $paymentAmount    = $total;
            $paymentMethod    = $request->payment_method;
            $merchantOrderId  = $invoice;
            $productDetails   = $product->name;
            $email            = $data['email'];
            $phoneNumber      = $data['phone'];
            $callbackUrl      = env('DUITKU_CALLBACK_URL');
            $returnUrl        = env('DUITKU_RETURN_URL');

            $signature = hash('sha256', $merchantCode . $merchantOrderId . $paymentAmount . $merchantKey);

            $payload = [
                'merchantCode'    => $merchantCode,
                'paymentAmount'   => $paymentAmount,
                'paymentMethod'   => $paymentMethod,
                'merchantOrderId' => $merchantOrderId,
                'productDetails'  => $productDetails,
                'email'           => $email,
                'phoneNumber'     => $phoneNumber,
                'callbackUrl'     => $callbackUrl,
                'returnUrl'       => $returnUrl,
                'signature'       => $signature,
                'expiryPeriod'    => 60,
            ];

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post('https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry', $payload);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['paymentUrl']) && isset($result['reference'])) {
                    $order->update([
                        'payment_url' => $result['paymentUrl'],
                        'reference'   => $result['reference'],
                    ]);
                    return redirect()->away($result['paymentUrl']); // langsung ke payment
                } else {
                    Log::warning('Duitku missing fields', $result);
                    return redirect()->route('checkout.step2')->with('error', 'Gagal mendapatkan URL pembayaran.');
                }
            } else {
                Log::error('Duitku API Error: '.$response->body());
                return redirect()->route('checkout.step2')->with('error', 'Payment gateway bermasalah.');
            }
        }

        // Kalau COD: langsung ke sukses
        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat (COD).');
    }
}
