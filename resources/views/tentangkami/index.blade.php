@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Tentang Kami</h1>
            <div class="flex gap-2">
                <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Tambah Data
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
                        <th class="px-4 py-2 border">Gambar</th>
                        <th class="px-4 py-2 border">Judul</th>
                        <th class="px-4 py-2 border">Kategori</th>
                        <th class="px-4 py-2 border">Deskripsi</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tentangkamiTable">
                    @forelse ($tentangkami as $item)
                        <tr>
                            <td class="px-4 py-2 border">
                                @if ($item->image)
                                    <img src="{{ asset($item->image) }}"
                                        class="w-24 h-24 object-cover object-center rounded shadow-md aspect-square">
                                @else
                                    <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $item->title ?? '-' }}</td>
                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($item->category == 'Visi') bg-blue-100 text-blue-800
                                    @elseif($item->category == 'Misi') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border">
                                <div class="max-w-xs truncate" title="{{ strip_tags($item->description) }}">
                                    {!! Str::limit($item->description, 100) !!}
                                </div>
                            </td>
                            <td class="px-4 py-2 border space-x-1">
                                <button onclick="openEditModal(this)" data-tentangkami='@json($item)'
                                    class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>
                                <button onclick="deleteTentangkami({{ $item->id }})"
                                    class="text-red-600 hover:text-red-800 px-2 py-1 text-xs border border-red-300 rounded hover:bg-red-50">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data tentang kami. <button onclick="openAddModal()" class="text-blue-500 hover:underline">Tambah data pertama</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah Data Tentang Kami</h2>
            <form action="{{ route('tentangkami.store') }}" method="POST" enctype="multipart/form-data" id="addTentangkamiForm">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="display_on_home" class="mr-2" value="1"
                                {{ old('display_on_home') ? 'checked' : '' }} />
                            <span class="font-medium">Tampilkan di Homepage</span>
                        </label>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full border rounded p-2 text-sm"
                            placeholder="Masukkan judul..." value="{{ old('title') }}" />
                        @error('title')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Kategori <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full border rounded p-2 text-sm">
                            <option value="">Pilih Kategori</option>
                            <option value="Visi" {{ old('category') == 'Visi' ? 'selected' : '' }}>Visi</option>
                            <option value="Misi" {{ old('category') == 'Misi' ? 'selected' : '' }}>Misi</option>
                            <option value="Sejarah" {{ old('category') == 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                        </select>
                        @error('category')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" id="editorAddDescription" rows="4"
                                  class="w-full border rounded p-2 text-sm"
                                  placeholder="Masukkan deskripsi...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Upload Gambar <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="file" name="image" id="addImageInput"
                            onchange="previewImage(this, 'addPreview')" accept="image/png,image/jpg,image/jpeg"
                            class="hidden" required />

                        <div id="addUploadArea" onclick="document.getElementById('addImageInput').click()"
                            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="text-gray-600 mb-2">Klik untuk upload atau drag and drop</p>
                                <p class="text-sm text-gray-500">PNG, JPG, atau JPEG (MAX. 2MB)</p>
                            </div>
                        </div>

                        <div id="addPreview" class="mt-4"></div>
                        @error('image')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
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
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Edit Data Tentang Kami</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="display_on_home" id="editDisplayOnHome" class="mr-2"
                                value="1" />
                            <span class="font-medium">Tampilkan di Homepage</span>
                        </label>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="editTitle" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Kategori <span class="text-red-500">*</span></label>
                        <select name="category" id="editCategory" required class="w-full border rounded p-2 text-sm">
                            <option value="">Pilih Kategori</option>
                            <option value="Visi">Visi</option>
                            <option value="Misi">Misi</option>
                            <option value="Sejarah">Sejarah</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" id="editorEditDescription" rows="4"
                                  class="w-full border rounded p-2 text-sm"></textarea>
                    </div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Ganti Gambar</label>
                    <div class="relative">
                        <input type="file" name="image" id="editImageInput"
                            onchange="previewImage(this, 'editPreview')" accept="image/png,image/jpg,image/jpeg"
                            class="hidden" />

                        <div id="editUploadArea" onclick="document.getElementById('editImageInput').click()"
                            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="text-gray-600 mb-2">Klik untuk upload atau drag and drop</p>
                                <p class="text-sm text-gray-500">PNG, JPG, atau JPEG (MAX. 2MB)</p>
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
            setupDragAndDrop();

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
        });

        // Setup drag and drop functionality
        function setupDragAndDrop() {
            setupDragAndDropForElement('addUploadArea', 'addImageInput');
            setupDragAndDropForElement('editUploadArea', 'editImageInput');
        }

        function setupDragAndDropForElement(uploadAreaId, inputId) {
            const uploadArea = document.getElementById(uploadAreaId);
            const fileInput = document.getElementById(inputId);

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.add('border-blue-400', 'bg-blue-50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
                }, false);
            });

            uploadArea.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    const previewId = uploadAreaId === 'addUploadArea' ? 'addPreview' : 'editPreview';
                    previewImage(fileInput, previewId);
                }
            }, false);
        }

        // Search function
        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#tentangkamiTable tr");

            rows.forEach(row => {
                let title = row.cells[1]?.textContent?.toLowerCase() || '';
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

        function deleteTentangkami(id) {
            Swal.fire({
                title: 'Hapus data ini?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/tentangkami/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                Swal.fire('Terhapus!', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
                        });
                }
            });
        }

        function openAddModal() {
            // Reset form
            document.getElementById('addTentangkamiForm').reset();
            document.getElementById('addPreview').innerHTML = '';
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
            const tentangkami = JSON.parse(button.getAttribute('data-tentangkami'));
            const form = document.getElementById('editForm');

            // Set form action
            form.action = `/tentangkami/${tentangkami.id}`;

            // Populate form fields
            document.getElementById('editTitle').value = tentangkami.title || '';
            document.getElementById('editCategory').value = tentangkami.category || '';
            document.getElementById('editDisplayOnHome').checked = tentangkami.display_on_home || false;

            // Set CKEditor content
            if (editDescriptionEditor) {
                editDescriptionEditor.setData(tentangkami.description || '');
            }

            // Handle image preview
            const editPreview = document.getElementById('editPreview');
            const editUploadArea = document.getElementById('editUploadArea');

            if (tentangkami.image) {
                const imagePath = tentangkami.image.startsWith('/') ? tentangkami.image : '/' + tentangkami.image;
                editPreview.innerHTML = `
                    <div class="relative inline-block">
                        <img src="${imagePath}" class="h-32 w-32 rounded-lg shadow-md object-cover border" alt="Current image">
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
                    Swal.fire('Error', 'File harus berupa gambar (PNG/JPG/JPEG)', 'error');
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

        // Show success/error messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}'
            });
        @endif

        // Show validation errors
        @if ($errors->any())
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: errorMessages,
                confirmButtonColor: '#d33'
            });
        @endif
    </script>
@endsection
