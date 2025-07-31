<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('product')->latest()->get();
        $products = Product::all();
        $selectedProduct = null;

        if ($request->has('product_id')) {
            $selectedProduct = Product::find($request->product_id);
        }

        return view('orders.index', compact('orders', 'products', 'selectedProduct'));
    }

    public function create(Product $product)
    {
        return view('orders.create', compact('product'));
    }

    public function payment($id)
    {
        $order = Order::with('product')->findOrFail($id);
        return view('orders.payment', compact('order'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric',
            // 'ongkir' => 'nullable|numeric',
            // 'payment_method' => 'required|string|in:bca,mandiri,bni,gopay,shopeepay,cod',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $order = Order::create($data);

        return redirect()->route('orders.payment', $order->id);
    }

    public function show($id)
    {
        $order = Order::with('product')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric',
            // 'ongkir' => 'nullable|numeric',
            // 'payment_method' => 'required|string|in:bca,mandiri,bni,gopay,shopeepay,cod',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order->update($validator->validated());

        return redirect()->route('orders.index')->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        if ($request->has('ids') && is_array($request->ids)) {
            Order::whereIn('id', $request->ids)->delete();
            return redirect()->route('orders.index')->with('success', 'Beberapa order berhasil dihapus.');
        }

        return redirect()->route('orders.index')->with('error', 'Tidak ada order yang dipilih.');
    }
}
