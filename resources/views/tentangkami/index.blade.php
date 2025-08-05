@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Tentang Kami</h1>
            <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Tambah Data
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border">Judul</th>
                        <th class="px-4 py-2 border">Kategori</th>
                        <th class="px-4 py-2 border">Deskripsi</th>
                        <th class="px-4 py-2 border">Gambar</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tentangkami as $item)
                        <tr>
                            <td class="px-4 py-2 border">{{ $item->title }}</td>
                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($item->category == 'Visi') bg-blue-100 text-blue-800
                                    @elseif($item->category == 'Misi') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border">{!! Str::limit(strip_tags($item->description), 100) !!}</td>
                            <td class="px-4 py-2 border">
                                @if($item->image)
                                    <img src="{{ asset($item->image) }}"
                                         class="w-24 h-24 object-cover object-center rounded shadow-md aspect-square">
                                @else
                                    <span class="text-gray-400 text-sm">No Image</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border space-x-1">
                                <button onclick="openEditModal({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>
                                <button onclick="confirmDelete({{ $item->id }})"
                                    class="text-red-600 hover:text-red-800 px-2 py-1 text-xs border border-red-300 rounded hover:bg-red-50">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data. <button onclick="openAddModal()" class="text-blue-500 hover:underline">Tambah data pertama</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah Data Tentang Kami</h2>
            <form action="{{ route('tentangkami.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full border rounded p-2 text-sm"
                               placeholder="Masukkan judul..." />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Kategori <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full border rounded p-2 text-sm">
                            <option value="">Pilih Kategori</option>
                            <option value="Visi">Visi</option>
                            <option value="Misi">Misi</option>
                            <option value="Sejarah">Sejarah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" id="editorAddDescription" rows="4"
                                  class="w-full border rounded p-2 text-sm"
                                  placeholder="Masukkan deskripsi..."></textarea>
                    </div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Upload Gambar <span class="text-red-500">*</span></label>
                    <div class="relative mb-4">
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
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Edit Data Tentang Kami</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">

                <div class="grid grid-cols-1 gap-4">
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
                    <div>
                        <label class="block mb-1 font-medium">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" id="editorEditDescription" rows="4"
                                  class="w-full border rounded p-2 text-sm"></textarea>
                    </div>
                </div>

                <!-- Current Image Section -->
                <div class="mt-4" id="currentImageSection" style="display: none;">
                    <label class="block mb-2 font-medium">Gambar Saat Ini</label>
                    <div id="currentImageContainer" class="mb-4"></div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Ganti Gambar (Opsional)</label>
                    <div class="relative mb-4">
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
                                <p class="text-sm text-gray-500">PNG, JPG, atau GIF (MAX. 2MB) - Opsional</p>
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

    <script>
        window.tentangkami = @json($tentangkami);
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <script>
        // Global variables for CKEditor instances
        let addDescriptionEditor = null;
        let editDescriptionEditor = null;
        let editorsInitialized = false;

        // Initialize CKEditor when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeEditors();
            setupDragAndDrop();
            setupFormHandlers();
            setupModalEvents();
        });

        // Function to initialize CKEditor instances
        function initializeEditors() {
            // Initialize CKEditor for Add Modal
            ClassicEditor
                .create(document.querySelector('#editorAddDescription'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', '|',
                            'undo', 'redo'
                        ]
                    }
                })
                .then(editor => {
                    addDescriptionEditor = editor;
                    checkEditorsInitialized();
                })
                .catch(error => {
                    console.error('Error initializing add editor:', error);
                });

            // Initialize CKEditor for Edit Modal
            ClassicEditor
                .create(document.querySelector('#editorEditDescription'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', '|',
                            'undo', 'redo'
                        ]
                    }
                })
                .then(editor => {
                    editDescriptionEditor = editor;
                    checkEditorsInitialized();
                })
                .catch(error => {
                    console.error('Error initializing edit editor:', error);
                });
        }

        // Check if both editors are initialized
        function checkEditorsInitialized() {
            if (addDescriptionEditor && editDescriptionEditor) {
                editorsInitialized = true;
                console.log('All CKEditor instances initialized successfully');
            }
        }

        // Setup form handlers
        function setupFormHandlers() {
            // Add form submit handler
            const addForm = document.querySelector('#addModal form');
            if (addForm) {
                addForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Sync CKEditor content with textarea before validation
                    if (addDescriptionEditor) {
                        const description = addDescriptionEditor.getData();
                        document.querySelector('#editorAddDescription').value = description;
                    }

                    // Validate required fields
                    const title = this.querySelector('input[name="title"]').value.trim();
                    const category = this.querySelector('select[name="category"]').value;
                    const description = addDescriptionEditor ? addDescriptionEditor.getData().trim() : '';
                    const imageFile = document.getElementById('addImageInput').files[0];

                    // Validation checks
                    if (!title) {
                        Swal.fire('Error', 'Judul harus diisi', 'error');
                        this.querySelector('input[name="title"]').focus();
                        return false;
                    }

                    if (!category) {
                        Swal.fire('Error', 'Kategori harus dipilih', 'error');
                        this.querySelector('select[name="category"]').focus();
                        return false;
                    }

                    if (!description) {
                        Swal.fire('Error', 'Deskripsi harus diisi', 'error');
                        addDescriptionEditor.editing.view.focus();
                        return false;
                    }

                    if (!imageFile) {
                        Swal.fire('Error', 'Gambar harus diupload', 'error');
                        return false;
                    }

                    // If all validation passes, submit the form
                    console.log('Form validation passed, submitting...');

                    // Show loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form using native submit
                    this.submit();
                });
            }

            // Edit form submit handler
            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Sync CKEditor content with textarea before validation
                    if (editDescriptionEditor) {
                        const description = editDescriptionEditor.getData();
                        document.querySelector('#editorEditDescription').value = description;
                    }

                    // Validate required fields
                    const title = document.getElementById('editTitle').value.trim();
                    const category = document.getElementById('editCategory').value;
                    const description = editDescriptionEditor ? editDescriptionEditor.getData().trim() : '';

                    if (!title) {
                        Swal.fire('Error', 'Judul harus diisi', 'error');
                        document.getElementById('editTitle').focus();
                        return false;
                    }

                    if (!category) {
                        Swal.fire('Error', 'Kategori harus dipilih', 'error');
                        document.getElementById('editCategory').focus();
                        return false;
                    }

                    if (!description) {
                        Swal.fire('Error', 'Deskripsi harus diisi', 'error');
                        editDescriptionEditor.editing.view.focus();
                        return false;
                    }

                    // Show loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form
                    this.submit();
                });
            }
        }

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const uploadAreaId = previewId.replace('Preview', 'UploadArea');
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
                            <button type="button" onclick="removePreview('${previewId}', '${uploadAreaId}', '${input.id}')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
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

        function removePreview(previewId, uploadAreaId, inputId) {
            document.getElementById(previewId).innerHTML = '';
            document.getElementById(uploadAreaId).style.display = 'block';
            document.getElementById(inputId).value = '';
        }

        function openAddModal() {
            // Wait for editors to be initialized
            if (!editorsInitialized) {
                setTimeout(() => openAddModal(), 100);
                return;
            }

            // Reset form
            const form = document.querySelector('#addModal form');
            form.reset();

            // Reset CKEditor content
            if (addDescriptionEditor) {
                addDescriptionEditor.setData('');
            }

            // Reset file input
            document.getElementById('addImageInput').value = '';
            document.getElementById('addPreview').innerHTML = '';
            document.getElementById('addUploadArea').style.display = 'block';

            // Show modal
            document.getElementById('addModal').classList.remove('hidden');

            // Focus on first input after modal is shown
            setTimeout(() => {
                const titleInput = document.querySelector('#addModal input[name="title"]');
                if (titleInput) {
                    titleInput.focus();
                }
            }, 300);
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id) {
            // Wait for editors to be initialized
            if (!editorsInitialized) {
                setTimeout(() => openEditModal(id), 100);
                return;
            }

            const tentangkamiData = window.tentangkami?.find(item => item.id == id);

            if (!tentangkamiData) {
                Swal.fire('Error', 'Data tidak ditemukan', 'error');
                return;
            }

            // Set form action
            const form = document.getElementById('editForm');
            form.action = `/tentangkami/${tentangkamiData.id}`;

            // Populate form fields
            document.getElementById('editId').value = tentangkamiData.id || '';
            document.getElementById('editTitle').value = tentangkamiData.title || '';
            document.getElementById('editCategory').value = tentangkamiData.category || '';

            // Set CKEditor content
            if (editDescriptionEditor) {
                editDescriptionEditor.setData(tentangkamiData.description || '');
            }

            // Handle current image
            const currentImageContainer = document.getElementById('currentImageContainer');
            const currentImageSection = document.getElementById('currentImageSection');

            currentImageContainer.innerHTML = '';
            if (tentangkamiData.image) {
                const imagePath = tentangkamiData.image.startsWith('/') ? tentangkamiData.image : '/' + tentangkamiData.image;
                currentImageContainer.innerHTML = `
                    <div class="relative inline-block">
                        <img src="${imagePath}" class="h-32 w-32 rounded-lg shadow-md object-cover border" alt="Current Image">
                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 text-center rounded-b-lg">
                            Gambar Saat Ini
                        </div>
                    </div>
                `;
                currentImageSection.style.display = 'block';
            } else {
                currentImageSection.style.display = 'none';
            }

            // Reset new file input
            document.getElementById('editImageInput').value = '';
            document.getElementById('editPreview').innerHTML = '';
            document.getElementById('editUploadArea').style.display = 'block';

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');

            // Focus on first input after modal is shown
            setTimeout(() => {
                const titleInput = document.getElementById('editTitle');
                if (titleInput) {
                    titleInput.focus();
                }
            }, 300);
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Confirm Delete Data
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/tentangkami/${id}`;
                    form.submit();
                }
            });
        }

        // Setup modal events
        function setupModalEvents() {
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
        }

        // Setup drag and drop functionality
        function setupDragAndDrop() {
            setupDragAndDropForElement('addUploadArea', 'addImageInput');
            setupDragAndDropForElement('editUploadArea', 'editImageInput');
        }

        function setupDragAndDropForElement(uploadAreaId, inputId) {
            const uploadArea = document.getElementById(uploadAreaId);
            const fileInput = document.getElementById(inputId);

            if (!uploadArea || !fileInput) return;

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
                    const previewId = uploadAreaId.replace('UploadArea', 'Preview');
                    previewImage(fileInput, previewId);
                }
            }, false);
        }

        // Check for session messages and show SweetAlert
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        @endif

        @if ($errors->any())
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                text: errorMessages,
                confirmButtonColor: '#d33'
            });
        @endif
    </script>
@endsection
