@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4 text-red-600">Pembayaran Gagal</h2>
    <p>Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.</p>
    <a href="{{ url()->previous() }}" class="underline">Kembali</a>
</div>
@endsection
