@extends('layouts.app')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Manajemen Order</h1>
        <div>
            <input type="text" id="searchInput" placeholder="Cari nama/email" class="border px-2 py-1 rounded">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Produk</th>
                    <th class="border px-4 py-2">Metode Pembayaran</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="orderTable">
                @foreach($orders as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $order->name }}</td>
                    <td class="border px-4 py-2">{{ $order->email }}</td>
                    <td class="border px-4 py-2">{{ $order->product->title ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($order->payment_method) }}</td>
                    <td class="border px-4 py-2">
                        @if($order->status == 'paid')
                        <span class="text-green-600 font-semibold">Lunas</span>
                        @else
                        <span class="text-red-600 font-semibold">Belum Bayar</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('orders.show', $order->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">Detail</a>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin ingin menghapus order ini?')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#orderTable tr');

        rows.forEach(row => {
            const name = row.children[1].innerText.toLowerCase();
            const email = row.children[2].innerText.toLowerCase();
            if (name.includes(filter) || email.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
