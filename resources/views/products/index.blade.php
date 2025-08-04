@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Produk Digital</h1>
            <div class="flex gap-2">
                <button id="bulkDeleteBtn" onclick="bulkDelete()"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>
                    Hapus Terpilih
                </button>
                <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Tambah Produk
                </button>
            </div>
        </div>

        <!-- Search Input -->
        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan judul..."
                class="border px-3 py-2 w-full rounded text-sm" onkeyup="searchTable()">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border"><input type="checkbox" id="selectAll"></th>
                        <th class="px-4 py-2 border">Gambar</th>
                        <th class="px-4 py-2 border">Judul</th>
                        <th class="px-4 py-2 border">Harga</th>
                        <th class="px-4 py-2 border">Diskon</th>
                        <th class="px-4 py-2 border">Disusun</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productTable">
                    @foreach ($products as $item)
                        <tr>
                            <td class="px-4 py-2 border">
                                <input type="checkbox" name="product_ids[]" value="{{ $item->id }}" class="rowCheckbox"
                                    onchange="updateBulkDeleteButton()">
                            </td>
                            <td class="px-4 py-2 border">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                        class="w-24 h-24 object-cover object-center rounded shadow-md aspect-square">
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $item->title }}</td>
                            <td class="px-4 py-2 border">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">
                                @if($item->discount)
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                        {{ $item->discount }}%
                                    </span>
                                @else
                                    <span class="text-gray-400">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $item->disusun }}</td>
                            <td class="px-4 py-2 border space-x-1">
                                <a href="{{ route('products.show', $item->id) }}"
                                    class="text-green-600 hover:text-green-800 px-2 py-1 text-xs border border-green-300 rounded hover:bg-green-50 inline-block">Detail</a>
                                <button onclick="openEditModal(this)" data-product='@json($item)'
                                    class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form id="bulkDeleteForm" method="POST" action="{{ route('products.bulkDelete') }}">
        @csrf
    </form>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-4xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah Produk</h2>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Judul</label>
                        <input type="text" name="title" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Harga</label>
                        <input type="number" name="price" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Diskon (%)</label>
                        <input type="number" name="discount" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Disusun Oleh</label>
                        <input type="text" name="disusun" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Jumlah Modul</label>
                        <input type="number" name="jumlah_modul" required min="1" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Bahasa</label>
                        <input type="text" name="bahasa" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div class="col-span-3">
                        <label class="block mb-1 font-medium">Deskripsi</label>
                        <textarea name="description" id="editorAddDescription" rows="4" class="w-full border rounded p-2 text-sm"></textarea>
                    </div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Upload Gambar <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="file" name="image" id="addImageInput" onchange="previewImage(this, 'addPreview')"
                            accept="image/png,image/jpg,image/jpeg" class="hidden" required />

                        <div id="addUploadArea" onclick="document.getElementById('addImageInput').click()"
                             class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-600 mb-2">Klik untuk upload atau drag and drop</p>
                                <p class="text-sm text-gray-500">PNG, JPG, atau GIF (MAX. 2MB)</p>
                            </div>
                        </div>

                        <div id="addPreview" class="mt-4"></div>
                    </div>
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
        <div class="bg-white rounded-lg w-full max-w-4xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Edit Produk</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Judul</label>
                        <input type="text" name="title" id="editTitle" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Harga</label>
                        <input type="number" name="price" id="editPrice" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Diskon (%)</label>
                        <input type="number" name="discount" id="editDiscount" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Disusun Oleh</label>
                        <input type="text" name="disusun" id="editDisusun" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Jumlah Modul</label>
                        <input type="number" name="jumlah_modul" id="editJumlahModul" required min="1" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Bahasa</label>
                        <input type="text" name="bahasa" id="editBahasa" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div class="col-span-3">
                        <label class="block mb-1 font-medium">Deskripsi</label>
                        <textarea name="description" id="editorEditDescription" rows="4" class="w-full border rounded p-2 text-sm"></textarea>
                    </div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Ganti Gambar</label>
                    <div class="relative">
                        <input type="file" name="image" id="editImageInput" onchange="previewImage(this, 'editPreview')"
                            accept="image/png,image/jpg,image/jpeg" class="hidden" />

                        <div id="editUploadArea" onclick="document.getElementById('editImageInput').click()"
                             class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-600 mb-2">Klik untuk upload atau drag and drop</p>
                                <p class="text-sm text-gray-500">PNG, JPG, atau GIF (MAX. 2MB)</p>
                            </div>
                        </div>

                        <div id="editPreview" class="mt-4"></div>
                    </div>
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
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <script>
        // Global variables for CKEditor instances
        let addDescriptionEditor = null;
        let editDescriptionEditor = null;

        // Initialize CKEditor when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor for Add Modal
            ClassicEditor
                .create(document.querySelector('#editorAddDescription'))
                .then(editor => {
                    addDescriptionEditor = editor;
                })
                .catch(error => {
                    console.error('Error initializing add description editor:', error);
                });

            // Initialize CKEditor for Edit Modal
            ClassicEditor
                .create(document.querySelector('#editorEditDescription'))
                .then(editor => {
                    editDescriptionEditor = editor;
                })
                .catch(error => {
                    console.error('Error initializing edit description editor:', error);
                });

            // Initialize drag and drop
            setupDragAndDrop();
        });

        // Search function
        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#productTable tr");

            rows.forEach(row => {
                let title = row.cells[2]?.textContent?.toLowerCase() || '';
                const shouldShow = title.includes(input);
                row.style.display = shouldShow ? "" : "none";
            });
        }

        // Debounce for better performance
        let searchTimer;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(searchTable, 300);
        });

        function bulkDelete() {
            const checkedBoxes = document.querySelectorAll('.rowCheckbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            if (ids.length === 0) {
                Swal.fire('Tidak ada yang dipilih', '', 'warning');
                return;
            }

            Swal.fire({
                title: `Hapus ${ids.length} produk terpilih?`,
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('bulkDeleteForm');
                    form.innerHTML = '@csrf';
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    form.submit();
                }
            });
        }

        function updateBulkDeleteButton() {
            const checked = document.querySelectorAll('.rowCheckbox:checked');
            const btn = document.getElementById('bulkDeleteBtn');
            btn.disabled = checked.length === 0;
            btn.textContent = checked.length > 0 ? `Hapus Terpilih (${checked.length})` : 'Hapus Terpilih';
        }

        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.rowCheckbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteButton();
        });

        function openAddModal() {
            // Reset form
            document.querySelector('#addModal form').reset();
            document.getElementById('addPreview').innerHTML = '';

            // Show upload area and hide preview
            document.getElementById('addUploadArea').style.display = 'block';

            // Reset CKEditor content
            if (addDescriptionEditor) {
                addDescriptionEditor.setData('');
            }

            // Show modal
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(button) {
            const product = JSON.parse(button.getAttribute('data-product'));
            const form = document.getElementById('editForm');

            // Set form action
            form.action = `/products/${product.id}`;

            // Populate form fields
            document.getElementById('editTitle').value = product.title || '';
            document.getElementById('editPrice').value = product.price || '';
            document.getElementById('editDiscount').value = product.discount || '';
            document.getElementById('editDisusun').value = product.disusun || '';
            document.getElementById('editJumlahModul').value = product.jumlah_modul || '';
            document.getElementById('editBahasa').value = product.bahasa || '';

            // Set CKEditor content
            if (editDescriptionEditor) {
                editDescriptionEditor.setData(product.description || '');
            }

            // Handle image preview
            const editPreview = document.getElementById('editPreview');
            const editUploadArea = document.getElementById('editUploadArea');

            if (product.image) {
                editPreview.innerHTML = `
                    <div class="relative inline-block">
                        <img src="/storage/${product.image}" class="h-32 w-32 rounded-lg shadow-md object-cover border" alt="Current image">
                        <button type="button" onclick="removeCurrentImage('edit')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
                editUploadArea.style.display = 'none';
            } else {
                editPreview.innerHTML = '';
                editUploadArea.style.display = 'block';
            }

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function removeCurrentImage(modalType) {
            const previewId = modalType === 'edit' ? 'editPreview' : 'addPreview';
            const uploadAreaId = modalType === 'edit' ? 'editUploadArea' : 'addUploadArea';
            const inputId = modalType === 'edit' ? 'editImageInput' : 'addImageInput';

            document.getElementById(previewId).innerHTML = '';
            document.getElementById(uploadAreaId).style.display = 'block';
            document.getElementById(inputId).value = '';
        }

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const uploadAreaId = previewId === 'addPreview' ? 'addUploadArea' : 'editUploadArea';
            const uploadArea = document.getElementById(uploadAreaId);

            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Validate file type
                if (!file.type.match('image.*')) {
                    Swal.fire('Error', 'File harus berupa gambar (PNG/JPG)', 'error');
                    input.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('Error', 'Ukuran file maksimal 2MB', 'error');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="relative inline-block">
                            <img src="${e.target.result}" class="h-32 w-32 rounded-lg shadow-md object-cover border" alt="Preview">
                            <button type="button" onclick="removeCurrentImage('${previewId === 'addPreview' ? 'add' : 'edit'}')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    uploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                uploadArea.style.display = 'block';
            }
        }

        // Setup drag and drop functionality
        function setupDragAndDrop() {
            ['addUploadArea', 'editUploadArea'].forEach(id => {
                const element = document.getElementById(id);
                const inputId = id === 'addUploadArea' ? 'addImageInput' : 'editImageInput';
                const previewId = id === 'addUploadArea' ? 'addPreview' : 'editPreview';

                element.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    element.classList.add('border-blue-400', 'bg-blue-50');
                });

                element.addEventListener('dragleave', () => {
                    element.classList.remove('border-blue-400', 'bg-blue-50');
                });

                element.addEventListener('drop', (e) => {
                    e.preventDefault();
                    element.classList.remove('border-blue-400', 'bg-blue-50');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        document.getElementById(inputId).files = files;
                        previewImage(document.getElementById(inputId), previewId);
                    }
                });
            });
        }

        // Close modal when clicking outside
        document.getElementById('addModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
            }
        });
    </script>
@endsection
