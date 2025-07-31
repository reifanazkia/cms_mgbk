@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Kategori Anggota</h1>
            <div class="flex gap-2">
                <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Tambah Kategori
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama Kategori</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="categoryTable">
                    @foreach ($anggota as $index => $category)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $category->name }}</td>
                            <td class="px-4 py-2 border space-x-1">
                                <button onclick="openEditModal({{ $category->id }})"
                                    class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>

                                <form action="{{ route('category-anggota.destroy', $category->id) }}" method="POST"
                                    class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 px-2 py-1 text-xs border border-red-300 rounded hover:bg-red-50">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($anggota->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center p-4 text-gray-500">Belum ada data kategori.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.anggota = @json($anggota);
    </script>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4">
            <h2 class="text-lg font-semibold">Tambah Kategori</h2>
            <form action="{{ route('category-anggota.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama Kategori</label>
                    <input type="text" name="name" required class="w-full border rounded p-2 text-sm" />
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4">
            <h2 class="text-lg font-semibold">Edit Kategori</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama Kategori</label>
                    <input type="text" name="name" id="editName" required
                        class="w-full border rounded p-2 text-sm" />
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openAddModal() {
            document.querySelector('#addModal form').reset();
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id) {
            const categoryData = window.anggota?.find(category => category.id == id);

            if (!categoryData) {
                Swal.fire('Error', 'Data kategori tidak ditemukan', 'error');
                return;
            }

            const form = document.getElementById('editForm');
            form.action = "{{ url('/category-anggota/update') }}/" + categoryData.id;
            document.getElementById('editId').value = categoryData.id || '';
            document.getElementById('editName').value = categoryData.name || '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.getElementById('addModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddModal();
        });

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
            }
        });

        // SweetAlert for success & error messages
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error'
            });
        @endif

        // SweetAlert for delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data tidak bisa dikembalikan setelah dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
