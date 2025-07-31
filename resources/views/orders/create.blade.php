{{-- resources/views/orders/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Checkout Produk</h1>

    <div class="bg-white shadow rounded p-6 mb-8">
        <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
        <p class="text-gray-700 mb-2">{{ $product->description }}</p>
        <p class="text-lg font-bold">Rp {{ number_format($product->final_price, 0, ',', '.') }}</p>
    </div>

    <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
        @csrf

        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="price" value="{{ $product->final_price }}">

        <div>
            <label class="block font-medium">Nama Lengkap</label>
            <input type="text" name="customer_name" class="w-full border rounded px-3 py-2" value="{{ old('customer_name') }}" required>
            @error('customer_name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">No. Telepon</label>
            <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="{{ old('phone') }}" required>
            @error('phone') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Provinsi</label>
            <input type="text" name="province" class="w-full border rounded px-3 py-2" value="{{ old('province') }}">
            @error('province') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Kota</label>
            <input type="text" name="city" class="w-full border rounded px-3 py-2" value="{{ old('city') }}">
            @error('city') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Alamat Lengkap</label>
            <textarea name="address" class="w-full border rounded px-3 py-2">{{ old('address') }}</textarea>
            @error('address') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Catatan Tambahan</label>
            <textarea name="note" class="w-full border rounded px-3 py-2">{{ old('note') }}</textarea>
            @error('note') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                Pesan Sekarang
            </button>
        </div>
    </form>
</div>
@endsection
