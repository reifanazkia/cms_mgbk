@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Kategori Store</h1>
            <div class="flex gap-2">
                <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Tambah Kategori
                </button>
            </div>
        </div>

        <!-- Search Input -->
        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan name kategori..."
                class="border px-3 py-2 w-full rounded text-sm" onkeyup="searchTable()">
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
                    @forelse ($kegiatan as $index => $category)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $category->name }}</td>
                            <td class="px-4 py-2 border space-x-1">
                                <button onclick="openEditModal(this)" data-item='@json($category)'
                                    class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>
                                <button onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')"
                                    class="text-red-600 hover:text-red-800 px-2 py-1 text-xs border border-red-300 rounded hover:bg-red-50">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center p-4 text-gray-500">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah Kategori Store</h2>
            <form id="addForm" action="{{ route('category-store.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                           class="w-full border rounded p-2 text-sm"
                           placeholder="Masukkan name kategori" />
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                    <button type="submit" id="addSubmitBtn"
                        class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Edit Kategori Store</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editname" required
                           class="w-full border rounded p-2 text-sm"
                           placeholder="Masukkan name kategori" />
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                    <button type="submit" id="editSubmitBtn"
                        class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Data untuk JavaScript
        window.kegiatan = @json($kegiatan);

        document.addEventListener('DOMContentLoaded', function() {
            // Modal click outside to close
            document.getElementById('addModal').addEventListener('click', function(e) {
                if (e.target === this) closeAddModal();
            });

            document.getElementById('editModal').addEventListener('click', function(e) {
                if (e.target === this) closeEditModal();
            });

            // Escape key to close modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddModal();
                    closeEditModal();
                }
            });

            // Form submit handlers
            document.getElementById('addForm').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('addSubmitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Menyimpan...';
            });

            document.getElementById('editForm').addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('editSubmitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Menyimpan...';
            });
        });

        // Search function
        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#categoryTable tr");

            rows.forEach(row => {
                if (row.cells.length < 3) return;

                let name = row.cells[1]?.textContent?.toLowerCase() || '';
                const shouldShow = name.includes(input);
                row.style.display = shouldShow ? "" : "none";
            });
        }

        // Debounced search
        let searchTimer;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(searchTable, 300);
        });

        // Modal functions
        function openAddModal() {
            document.getElementById('addForm').reset();
            document.getElementById('addSubmitBtn').disabled = false;
            document.getElementById('addSubmitBtn').innerHTML = 'Simpan';
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(button) {
            const data = JSON.parse(button.getAttribute('data-item'));
            const form = document.getElementById('editForm');

            // Set form action for update
            form.action = "{{ route('category-store.update', ':id') }}".replace(':id', data.id);

            // Fill form fields
            document.getElementById('editId').value = data.id || '';
            document.getElementById('editname').value = data.name || '';

            // Reset button state
            document.getElementById('editSubmitBtn').disabled = false;
            document.getElementById('editSubmitBtn').innerHTML = 'Simpan';

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Delete confirmation
        function confirmDelete(id, name) {
            Swal.fire({
                title: `Hapus kategori "${name}"?`,
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('category-store.destroy', ':id') }}".replace(':id', id);

                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                                    document.querySelector('input[name="_token"]')?.value;

                    if (!csrfToken) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'CSRF token tidak ditemukan!'
                        });
                        return;
                    }

                    // Add CSRF token
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = csrfToken;
                    form.appendChild(csrf);

                    // Add method override
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Success message
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3b82f6',
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        @endif

        // Error message
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3b82f6'
            });
        @endif

        // Validation errors
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach',
                confirmButtonColor: '#3b82f6'
            });
        @endif
    </script>
@endsection
