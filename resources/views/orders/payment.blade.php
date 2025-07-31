@extends('layouts.app')

@section('content')
<div class="px-6 py-8 max-w-4xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold mb-4">Pembayaran</h2>

    {{-- Produk --}}
    <div class="bg-white rounded shadow p-4 flex items-center gap-4">
        <div class="w-24 h-24 bg-gray-100 rounded overflow-hidden">
            @if ($order->product->image)
                <img src="{{ asset('storage/' . $order->product->image) }}" alt="Image"
                    class="w-full h-full object-cover">
            @else
                <div class="flex items-center justify-center h-full text-sm text-gray-500">Tidak ada gambar</div>
            @endif
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-semibold">{{ $order->product->title }}</h3>
            <p class="text-gray-600">{!! Str::limit(strip_tags($order->product->description), 100) !!}</p>
        </div>
        <div class="text-right">
            @if ($order->product->discount > 0)
                <div class="text-sm line-through text-gray-400 mb-1">Rp {{ number_format($order->product->price) }}</div>
                <div class="text-xl font-bold text-green-600">
                    Rp {{ number_format($order->price) }}
                </div>
            @else
                <div class="text-xl font-bold text-gray-800">
                    Rp {{ number_format($order->price) }}
                </div>
            @endif
        </div>
    </div>

    {{-- Alamat --}}
    <div class="bg-white rounded shadow p-4">
        <h4 class="text-lg font-semibold mb-2">Alamat Pengiriman</h4>
        <div class="text-gray-800">
            <p>{{ $order->customer_name }}</p>
            <p>{{ $order->phone }} • {{ $order->email }}</p>
            <p>{{ $order->address }}, {{ $order->city }}, {{ $order->province }}</p>
            @if ($order->note)
                <p class="text-sm italic mt-1 text-gray-500">Catatan: {{ $order->note }}</p>
            @endif
        </div>
    </div>

    {{-- Metode Pembayaran --}}
    <div class="bg-white rounded shadow p-4 space-y-4">
        <h4 class="text-lg font-semibold">Metode Pembayaran</h4>
        <div class="text-gray-800">{{ ucfirst($order->payment_method) }}</div>

        {{-- Simulasi QR atau Nomor Rekening --}}
        @if ($order->payment_method === 'qris')
            <div>
                <img src="{{ asset('images/qris-example.png') }}" alt="QRIS"
                    class="w-40 border rounded shadow">
                <p class="text-sm text-gray-500 mt-2">Silakan scan kode QR di atas untuk pembayaran.</p>
            </div>
        @else
            <div class="text-sm text-gray-700">
                Silakan transfer ke rekening berikut:<br>
                <span class="font-semibold">BANK ABC</span><br>
                No. Rekening: <span class="font-bold">1234567890</span><br>
                a.n. PT. Digital Contoh
            </div>
        @endif
    </div>

    {{-- Ringkasan --}}
    <div class="bg-white rounded shadow p-4">
        <h4 class="text-lg font-semibold mb-2">Ringkasan Pembayaran</h4>
        <div class="flex justify-between text-sm text-gray-700">
            <span>Subtotal</span>
            <span>Rp {{ number_format($order->price - $order->ongkir) }}</span>
        </div>
        <div class="flex justify-between text-sm text-gray-700">
            <span>Ongkir</span>
            <span>Rp {{ number_format($order->ongkir) }}</span>
        </div>
        <div class="flex justify-between font-semibold text-lg mt-2">
            <span>Total</span>
            <span>Rp {{ number_format($order->price) }}</span>
        </div>
    </div>

    {{-- Tombol --}}
    <div class="flex justify-end gap-4">
        <a href="{{ route('products.show', $order->product_id) }}"
            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
            Kembali
        </a>
        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded font-semibold transition">
            Proses Pembayaran
        </button>
    </div>
</div>
@endsection
