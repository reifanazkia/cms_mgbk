@extends('layouts.app')

@section('page_title', 'Detail Order')

@section('content')
<div class="container mt-6">
    <div class="bg-white p-6 rounded-xl shadow-md w-full max-w-4xl mx-auto">
        <h2 class="text-2xl font-semibold mb-6">Detail Order</h2>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <h4 class="font-semibold">Nama Customer</h4>
                <p>{{ $order->customer_name }}</p>
            </div>
            <div>
                <h4 class="font-semibold">Email</h4>
                <p>{{ $order->email }}</p>
            </div>
            <div>
                <h4 class="font-semibold">No. Telepon</h4>
                <p>{{ $order->phone }}</p>
            </div>
            <div>
                <h4 class="font-semibold">Provinsi</h4>
                <p>{{ $order->province }}</p>
            </div>
            <div>
                <h4 class="font-semibold">Kota</h4>
                <p>{{ $order->city }}</p>
            </div>
            <div>
                <h4 class="font-semibold">Alamat</h4>
                <p>{{ $order->address }}</p>
            </div>
            <div>
                <h4 class="font-semibold">Produk</h4>
                <p>{{ $order->product->title ?? '-' }}</p>
            </div>
            <div>
                <h4 class="font-semibold">Harga</h4>
                <p>Rp{{ number_format($order->price, 0, ',', '.') }}</p>
            </div>
            <div>
                <h4 class="font-semibold">Ongkir</h4>
                <p>Rp{{ number_format($order->ongkir, 0, ',', '.') }}</p>
            </div>
            <div>
                <h4 class="font-semibold">Metode Pembayaran</h4>
                <p>{{ $order->payment_method }}</p>
            </div>
            <div class="col-span-2">
                <h4 class="font-semibold">Catatan</h4>
                <p>{{ $order->note }}</p>
            </div>
        </div>

        <a href="{{ route('order.index') }}" class="mt-4 inline-block px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">← Kembali</a>
    </div>
</div>
@endsection
