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

        <!-- Search Input -->
        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama kategori..."
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
                    @foreach ($anggota as $index => $category)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $category->name }}</td>
                            <td class="px-4 py-2 border space-x-1">
                                <button onclick="openEditModal(this)" data-item='@json($category)'
                                    class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>

                                <!-- ALTERNATIF 1: Form HTML langsung (Paling reliable) -->
                                <form action="{{ route('category-anggota.destroy', $category->id) }}" method="POST"
                                      style="display: inline;" onsubmit="return confirmDeleteSimple(event, '{{ $category->name }}')">
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
        <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah Kategori</h2>
            <form id="addForm" action="{{ route('category-anggota.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                           class="w-full border rounded p-2 text-sm"
                           placeholder="Masukkan nama kategori" />
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
            <h2 class="text-lg font-semibold">Edit Kategori</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" required
                           class="w-full border rounded p-2 text-sm"
                           placeholder="Masukkan nama kategori" />
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
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal when clicking outside
            document.getElementById('addModal').addEventListener('click', function(e) {
                if (e.target === this) closeAddModal();
            });

            document.getElementById('editModal').addEventListener('click', function(e) {
                if (e.target === this) closeEditModal();
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddModal();
                    closeEditModal();
                }
            });

            // Handle form submissions
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

        // Search functionality
        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#categoryTable tr");

            rows.forEach(row => {
                // Skip if it's the "no data" row
                if (row.cells.length < 3) return;

                let name = row.cells[1]?.textContent?.toLowerCase() || '';
                const shouldShow = name.includes(input);
                row.style.display = shouldShow ? "" : "none";
            });
        }

        // Debounce search input
        let searchTimer;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(searchTable, 300);
        });

        function openAddModal() {
            // Reset form
            document.getElementById('addForm').reset();
            // Show modal
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(button) {
            const data = JSON.parse(button.getAttribute('data-item'));

            const form = document.getElementById('editForm');
            form.action = "{{ url('/category-anggota/update') }}/" + data.id;
            document.getElementById('editId').value = data.id || '';
            document.getElementById('editName').value = data.name || '';

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // ALTERNATIF: Jika masih ingin menggunakan JavaScript delete (backup)
        function confirmDelete(id, name) {
            console.log('Delete attempt - ID:', id, 'Name:', name); // Debug

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
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';

                    // Gunakan route helper yang tepat
                    form.action = `{{ route('category-anggota.destroy', ':id') }}`.replace(':id', id);
                    console.log('Delete URL:', form.action); // Debug

                    form.style.display = 'none';

                    // CSRF Token handling yang lebih robust
                    let csrfToken = null;

                    // Coba ambil dari meta tag
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    if (csrfMeta && csrfMeta.content) {
                        csrfToken = csrfMeta.content;
                    }

                    // Fallback: ambil dari form yang sudah ada
                    if (!csrfToken) {
                        const existingForm = document.querySelector('form[method="POST"] input[name="_token"]');
                        if (existingForm) {
                            csrfToken = existingForm.value;
                        }
                    }

                    if (!csrfToken) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'CSRF token tidak ditemukan! Silakan refresh halaman.',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    // Add CSRF token
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = csrfToken;
                    form.appendChild(csrf);

                    // Add DELETE method
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);

                    // Submit form
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Function untuk form HTML langsung (RECOMMENDED)
        function confirmDeleteSimple(event, name) {
            event.preventDefault(); // Prevent default form submission

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
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit the form
                    event.target.submit();
                }
            });

            return false;
        }

        // Session alerts
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

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3b82f6'
            });
        @endif
    </script>
@endsection
