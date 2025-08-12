@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Tentang Kami</h1>
            <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Tambah Data
            </button>
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
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Judul</th>
                        <th class="px-4 py-2 border">Kategori</th>
                        <th class="px-4 py-2 border">Gambar</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tentangkamiTable">
                    @if($tentangkami && $tentangkami->count() > 0)
                        @foreach ($tentangkami as $index => $item)
                            <tr>
                                <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 border">{{ $item->title ?? 'N/A' }}</td>
                                <td class="px-4 py-2 border">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                        {{ $item->category->nama ?? 'Tidak ada kategori' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border">
                                    @if ($item->image)
                                        <img src="{{ asset($item->image) }}"
                                            class="w-24 h-24 object-cover object-center rounded shadow-md aspect-square">
                                    @else
                                        <span class="text-gray-400 text-xs">Tidak ada gambar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border space-x-1">
                                    <button onclick="openEditModal(this)" data-tentangkami='@json($item)'
                                        class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>
                                    <button onclick="confirmDelete({{ $item->id }})"
                                        class="text-red-600 hover:text-red-800 px-2 py-1 text-xs border border-red-300 rounded hover:bg-red-50">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-4 py-8 border text-center text-gray-500">
                                Tidak ada data tersedia
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah Data Tentang Kami</h2>
            <form id="addForm" action="{{ route('tentangkami.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">
                            <input type="checkbox" name="display_on_home" value="1" class="mr-2">
                            Tampilkan di Halaman Utama
                        </label>
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full border rounded p-2 text-sm" />
                        <p class="error-text text-red-500 text-xs mt-1 hidden">Judul wajib diisi</p>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_tentangkami_id" id="addCategorySelect" required
                            class="w-full border rounded p-2 text-sm">
                            <option value="">Pilih Kategori</option>
                            @if($categories && $categories->count() > 0)
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endforeach
                            @endif
                        </select>
                        <p class="error-text text-red-500 text-xs mt-1 hidden">Kategori wajib dipilih</p>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" id="addDescription" rows="4" class="w-full border rounded p-2 text-sm" required></textarea>
                        <p class="error-text text-red-500 text-xs mt-1 hidden">Deskripsi wajib diisi</p>
                    </div>
                </div>

                <!-- Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Upload Gambar <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="file" name="image" id="addImageInput" onchange="previewImage(this, 'addPreview')"
                            accept="image/png,image/jpg,image/jpeg,image/gif" required class="hidden" />
                        <p class="error-text text-red-500 text-xs mt-1 hidden">Gambar wajib diupload</p>

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
                                <p class="text-sm text-gray-500">PNG, JPG, JPEG, atau GIF (MAX. 2MB)</p>
                            </div>
                        </div>

                        <div id="addPreview" class="mt-4"></div>
                    </div>
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
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Edit Data Tentang Kami</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">
                            <input type="checkbox" name="display_on_home" id="editDisplayOnHome" value="1"
                                class="mr-2">
                            Tampilkan di Halaman Utama
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="editTitle" required
                            class="w-full border rounded p-2 text-sm" />
                        <p class="error-text text-red-500 text-xs mt-1 hidden">Judul wajib diisi</p>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_tentangkami_id" id="editCategorySelect" required
                            class="w-full border rounded p-2 text-sm">
                            <option value="">Pilih Kategori</option>
                            @if($categories && $categories->count() > 0)
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endforeach
                            @endif
                        </select>
                        <p class="error-text text-red-500 text-xs mt-1 hidden">Kategori wajib dipilih</p>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" id="editDescription" rows="4" class="w-full border rounded p-2 text-sm" required></textarea>
                        <p class="error-text text-red-500 text-xs mt-1 hidden">Deskripsi wajib diisi</p>
                    </div>
                </div>

                <!-- Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Ganti Gambar</label>
                    <div class="relative">
                        <input type="file" name="image" id="editImageInput"
                            onchange="previewImage(this, 'editPreview')" accept="image/png,image/jpg,image/jpeg,image/gif"
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
                                <p class="text-sm text-gray-500">PNG, JPG, JPEG, atau GIF (MAX. 2MB) - Opsional</p>
                            </div>
                        </div>

                        <div id="editPreview" class="mt-4"></div>
                    </div>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Global variables
        let addDescriptionEditor = null;
        let editDescriptionEditor = null;

        // Document ready
        $(document).ready(function() {
            console.log('Document ready');

            // Setup drag and drop
            setupDragAndDrop();

            // Close modal when clicking outside
            $('#addModal').on('click', function(e) {
                if (e.target === this) closeAddModal();
            });

            $('#editModal').on('click', function(e) {
                if (e.target === this) closeEditModal();
            });

            // Escape key to close modals
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddModal();
                    closeEditModal();
                }
            });

            // Form validation on submit
            $('#addForm').on('submit', function(e) {
                console.log('Add form submitted');

                // Get CKEditor data and update textarea
                if (addDescriptionEditor) {
                    const editorData = addDescriptionEditor.getData();
                    $('#addDescription').val(editorData);
                    console.log('Description data:', editorData);
                }

                // Basic validation
                let isValid = true;
                const title = $('input[name="title"]').val().trim();
                const category = $('#addCategorySelect').val();
                const description = addDescriptionEditor ? addDescriptionEditor.getData().trim() : $(
                    '#addDescription').val().trim();
                const image = $('#addImageInput')[0].files.length > 0;

                // Reset error states
                $('#addForm .error-text').addClass('hidden');
                $('#addForm [required]').removeClass('border-red-500');

                // Validate fields
                if (!title) {
                    showFieldError('input[name="title"]', 'Judul wajib diisi');
                    isValid = false;
                }

                if (!category) {
                    showFieldError('#addCategorySelect', 'Kategori wajib dipilih');
                    isValid = false;
                }

                if (!description) {
                    showFieldError('#addDescription', 'Deskripsi wajib diisi');
                    isValid = false;
                }

                if (!image) {
                    $('#addImageInput').siblings('.error-text').removeClass('hidden');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error',
                        text: 'Mohon lengkapi semua field yang wajib diisi.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }

                // Show loading state
                $('#addSubmitBtn').text('Menyimpan...').prop('disabled', true);
            });

            $('#editForm').on('submit', function(e) {
                console.log('Edit form submitted');

                // Get CKEditor data and update textarea
                if (editDescriptionEditor) {
                    const editorData = editDescriptionEditor.getData();
                    $('#editDescription').val(editorData);
                    console.log('Description data:', editorData);
                }

                // Basic validation
                let isValid = true;
                const title = $('#editTitle').val().trim();
                const category = $('#editCategorySelect').val();
                const description = editDescriptionEditor ? editDescriptionEditor.getData().trim() : $(
                    '#editDescription').val().trim();

                // Reset error states
                $('#editForm .error-text').addClass('hidden');
                $('#editForm [required]').removeClass('border-red-500');

                // Validate fields
                if (!title) {
                    showFieldError('#editTitle', 'Judul wajib diisi');
                    isValid = false;
                }

                if (!category) {
                    showFieldError('#editCategorySelect', 'Kategori wajib dipilih');
                    isValid = false;
                }

                if (!description) {
                    showFieldError('#editDescription', 'Deskripsi wajib diisi');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error',
                        text: 'Mohon lengkapi semua field yang wajib diisi.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return false;
                }

                // Show loading state
                $('#editSubmitBtn').text('Menyimpan...').prop('disabled', false);
            });
        });

        function showFieldError(selector, message) {
            $(selector).addClass('border-red-500');
            $(selector).siblings('.error-text').text(message).removeClass('hidden');
        }

        function openAddModal() {
            console.log('Opening add modal');

            // Reset form
            document.getElementById('addForm').reset();
            document.getElementById('addPreview').innerHTML = '';
            document.getElementById('addUploadArea').style.display = 'block';

            // Reset button state
            $('#addSubmitBtn').text('Simpan').prop('disabled', false);

            // Reset error states
            $('#addForm .error-text').addClass('hidden');
            $('#addForm [required]').removeClass('border-red-500');

            // Show modal
            document.getElementById('addModal').classList.remove('hidden');

            // Initialize CKEditor after modal is visible
            setTimeout(function() {
                if (!addDescriptionEditor) {
                    // Remove required attribute temporarily to prevent browser validation conflict
                    $('#addDescription').removeAttr('required');

                    ClassicEditor
                        .create(document.querySelector('#addDescription'), {
                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                                '|', 'outdent', 'indent', '|', 'blockQuote', 'undo', 'redo'
                            ]
                        })
                        .then(editor => {
                            addDescriptionEditor = editor;
                            console.log('Add editor initialized');
                        })
                        .catch(error => {
                            console.error('Error initializing add editor:', error);
                            // Restore required attribute if editor fails
                            $('#addDescription').attr('required', 'required');
                        });
                }
            }, 100);
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');

            // Destroy editor safely
            if (addDescriptionEditor) {
                try {
                    addDescriptionEditor.destroy();
                } catch (error) {
                    console.warn('Error destroying add editor:', error);
                }
                addDescriptionEditor = null;
            }

            // Restore required attribute
            $('#addDescription').attr('required', 'required');

            // Reset button state
            $('#addSubmitBtn').text('Simpan').prop('disabled', false);
        }

        function openEditModal(button) {
            console.log('Opening edit modal');

            const tentangkami = JSON.parse(button.getAttribute('data-tentangkami'));
            const form = document.getElementById('editForm');

            // Set form action and values
            form.action = `/tentangkami/${tentangkami.id}`;
            document.getElementById('editTitle').value = tentangkami.title || '';
            document.getElementById('editCategorySelect').value = tentangkami.category_tentangkami_id || '';
            document.getElementById('editDisplayOnHome').checked = tentangkami.display_on_home == 1;

            // Reset button state
            $('#editSubmitBtn').text('Simpan').prop('disabled', false);

            // Handle image preview
            const editPreview = document.getElementById('editPreview');
            const editUploadArea = document.getElementById('editUploadArea');

            if (tentangkami.image) {
                const imageUrl = tentangkami.image.startsWith('http') ? tentangkami.image :
                    `{{ asset('') }}${tentangkami.image}`;
                editPreview.innerHTML = `
            <div class="relative inline-block">
                <img src="${imageUrl}" class="h-32 w-32 rounded-lg shadow-md object-cover border">
                <button type="button" onclick="removeCurrentImage('edit')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>`;
                editUploadArea.style.display = 'none';
            } else {
                editPreview.innerHTML = '';
                editUploadArea.style.display = 'block';
            }

            // Reset error states
            $('#editForm .error-text').addClass('hidden');
            $('#editForm [required]').removeClass('border-red-500');

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');

            // Initialize CKEditor after modal is visible
            setTimeout(function() {
                if (!editDescriptionEditor) {
                    // Remove required attribute temporarily to prevent browser validation conflict
                    $('#editDescription').removeAttr('required');

                    ClassicEditor
                        .create(document.querySelector('#editDescription'), {
                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                                '|', 'outdent', 'indent', '|', 'blockQuote', 'undo', 'redo'
                            ]
                        })
                        .then(editor => {
                            editDescriptionEditor = editor;
                            // Set content after editor is ready
                            editor.setData(tentangkami.description || '');
                            console.log('Edit editor initialized');
                        })
                        .catch(error => {
                            console.error('Error initializing edit editor:', error);
                            // Restore required attribute if editor fails
                            $('#editDescription').attr('required', 'required');
                        });
                } else {
                    // If editor already exists, just set the data
                    editDescriptionEditor.setData(tentangkami.description || '');
                }
            }, 100);
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');

            // Destroy editor safely
            if (editDescriptionEditor) {
                try {
                    editDescriptionEditor.destroy();
                } catch (error) {
                    console.warn('Error destroying edit editor:', error);
                }
                editDescriptionEditor = null;
            }

            // Restore required attribute
            $('#editDescription').attr('required', 'required');

            // Reset button state
            $('#editSubmitBtn').text('Simpan').prop('disabled', false);
        }

        function setupDragAndDrop() {
            setupDragAndDropForElement('addUploadArea', 'addImageInput');
            setupDragAndDropForElement('editUploadArea', 'editImageInput');
        }

        function setupDragAndDropForElement(uploadAreaId, inputId) {
            const uploadArea = document.getElementById(uploadAreaId);
            const fileInput = document.getElementById(inputId);

            if (!uploadArea || !fileInput) return;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(ev =>
                uploadArea.addEventListener(ev, e => {
                    e.preventDefault();
                    e.stopPropagation();
                })
            );

            ['dragenter', 'dragover'].forEach(ev =>
                uploadArea.addEventListener(ev, () => uploadArea.classList.add('border-blue-400', 'bg-blue-50'))
            );

            ['dragleave', 'drop'].forEach(ev =>
                uploadArea.addEventListener(ev, () => uploadArea.classList.remove('border-blue-400', 'bg-blue-50'))
            );

            uploadArea.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    previewImage(fileInput, uploadAreaId === 'addUploadArea' ? 'addPreview' : 'editPreview');
                }
            });
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'File harus berupa gambar',
                        confirmButtonColor: '#3b82f6'
                    });
                    input.value = '';
                    return;
                }

                // Validate file size (2MB = 2 * 1024 * 1024 bytes)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ukuran file maksimal 2MB',
                        confirmButtonColor: '#3b82f6'
                    });
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                <div class="relative inline-block">
                    <img src="${e.target.result}" class="h-32 w-32 rounded-lg shadow-md object-cover border">
                    <button type="button" onclick="removeCurrentImage('${previewId === 'addPreview' ? 'add' : 'edit'}')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>`;
                    uploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                uploadArea.style.display = 'block';
            }
        }

        function removeCurrentImage(modalType) {
            const previewId = modalType === 'edit' ? 'editPreview' : 'addPreview';
            const uploadAreaId = modalType === 'edit' ? 'editUploadArea' : 'addUploadArea';
            const inputId = modalType === 'edit' ? 'editImageInput' : 'addImageInput';

            document.getElementById(previewId).innerHTML = '';
            document.getElementById(uploadAreaId).style.display = 'block';
            document.getElementById(inputId).value = '';
        }

        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#tentangkamiTable tr");

            rows.forEach(row => {
                let title = row.cells[1]?.textContent?.toLowerCase() || '';
                let category = row.cells[2]?.textContent?.toLowerCase() || '';
                row.style.display = (title.includes(input) || category.includes(input)) ? "" : "none";
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
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
                    form.action = `/tentangkami/${id}`;
                    form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Session messages
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

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach',
                confirmButtonColor: '#3b82f6'
            });
        @endif
    </script>
@endsection
