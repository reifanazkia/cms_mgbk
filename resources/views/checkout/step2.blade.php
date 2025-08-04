@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    {{-- Breadcrumb / progress --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm">
            <div class="text-gray-400">1. Informasi Pembeli</div>
            <div class="text-purple-600 font-semibold">› Pembayaran</div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Summary -->
        <div class="border rounded p-6">
            <h3 class="font-semibold mb-4">Ringkasan Pesanan</h3>

            <div class="mb-4">
                <div class="text-sm font-medium">Informasi Pembeli</div>
                <div class="text-xs text-gray-600">
                    {{ $data['customer_name'] ?? '-' }} • {{ $data['email'] ?? '-' }} • {{ $data['phone'] ?? '-' }}
                </div>
                <div class="text-xs text-gray-600">
                    {{ $data['address'] ?? '-' }}, {{ $data['city'] ?? '-' }}, {{ $data['province'] ?? '-' }}
                </div>
            </div>

            <div class="flex justify-between mb-2">
                <div>Produk</div>
                <div>{{ $product->title ?? $product->name }}</div>
            </div>

            <div class="flex justify-between mb-2">
                <div>Harga</div>
                <div>
                    @if($product->has_discount)
                        <div class="flex flex-col">
                            <div>
                                <span class="line-through text-sm text-gray-500">
                                    {{ $product->formatted_price }}
                                </span>
                                <span class="ml-1 font-semibold">
                                    {{ $product->formatted_final_price }}
                                </span>
                            </div>
                            <div class="text-green-600 text-xs mt-1">
                                Diskon -{{ $product->discount }}%
                            </div>
                        </div>
                    @else
                        <span class="font-semibold">
                            {{ $product->formatted_price }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex justify-between mb-2">
                <div>Ongkir</div>
                <div>Rp 0</div>
            </div>

            <div class="flex justify-between font-semibold text-lg mt-4">
                <div>Total</div>
                <div>{{ $product->formatted_final_price }}</div>
            </div>
        </div>

        <!-- Payment -->
        <div class="border rounded p-6">
            <form action="{{ route('checkout.processStep2') }}" method="POST">
                @csrf

                {{-- Metode Pembayaran --}}
                <div class="mb-4">
                    <h4 class="font-semibold mb-2">Metode Pembayaran</h4>
                    @error('payment_method')
                        <div class="text-red-600 text-sm mb-1">{{ $message }}</div>
                    @enderror
                    <div class="flex flex-col gap-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_method" value="VA" {{ old('payment_method') === 'VA' ? 'checked' : '' }} required>
                            <span class="ml-2">Bank Virtual Account</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_method" value="QRIS" {{ old('payment_method') === 'QRIS' ? 'checked' : '' }}>
                            <span class="ml-2">QRIS</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_method" value="COD" {{ old('payment_method') === 'COD' ? 'checked' : '' }}>
                            <span class="ml-2">Cash on Delivery</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <a href="{{ route('checkout.step1', ['product' => $product->id]) }}"
                        class="px-4 py-2 border rounded">Ubah Info</a>
                    <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded">Proses Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
