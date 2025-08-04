@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-6">
        <div class="flex items-center gap-2">
            <div class="font-semibold">1. Informasi Pembeli</div>
            <div class="text-gray-400">›</div>
            <div class="text-gray-400">2. Pembayaran</div>
        </div>
    </div>

    <form action="{{ route('checkout.processStep1') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block">Nama</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="col-span-2">
                <label class="block">Alamat</label>
                <textarea name="address" required class="w-full border rounded px-3 py-2">{{ old('address') }}</textarea>
            </div>
            <div>
                <label class="block">Provinsi</label>
                <input type="text" name="province" value="{{ old('province') }}" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block">Kota</label>
                <input type="text" name="city" value="{{ old('city') }}" required class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded">Kembali</a>
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded">Lanjut ke Pembayaran</button>
        </div>
    </form>
</div>
@endsection
