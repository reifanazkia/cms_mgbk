@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4 text-green-600">Pembayaran Berhasil</h2>
    <p>Invoice: {{ $order->invoice_number }}</p>
    <p>Produk: {{ $order->product->title ?? $order->product->name }}</p>
    <p>Nama: {{ $order->customer_name }}</p>
    <p>Total: Rp {{ number_format($order->total,0,',','.') }}</p>
    <p>Status: {{ ucfirst($order->status) }}</p>
</div>
@endsection
