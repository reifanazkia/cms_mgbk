<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    // Endpoint yang dipanggil oleh Duitku (server-to-server)
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Log untuk debugging (boleh dihapus kalau sudah stabil)
        Log::info('Duitku callback received', $payload);

        $invoice = $payload['merchantOrderId'] ?? null;
        $status = strtoupper($payload['status'] ?? '');

        if (! $invoice) {
            Log::warning('Callback tidak memiliki merchantOrderId', $payload);
            return response('bad request', 400);
        }

        $order = Order::where('invoice_number', $invoice)->first();
        if (! $order) {
            Log::warning('Order tidak ditemukan dari callback', ['invoice' => $invoice]);
            return response('order not found', 404);
        }

        // Update status berdasarkan status dari Duitku
        if (in_array($status, ['SUCCESS', 'PAID'])) {
            $order->update(['status' => 'paid']);
        } elseif ($status === 'EXPIRED') {
            $order->update(['status' => 'expired']);
        }

        Log::info('Order status updated via callback', [
            'invoice' => $invoice,
            'status'  => $order->status,
        ]);

        // Acknowledge ke Duitku
        return response('OK', 200);
    }
}
