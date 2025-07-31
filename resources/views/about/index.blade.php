@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Tentang Kami</h1>
            <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Tambah About
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border">Sejarah</th>
                        <th class="px-4 py-2 border">Visi</th>
                        <th class="px-4 py-2 border">Misi</th>
                        <th class="px-4 py-2 border">Foto</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($abouts as $item)
                        <tr>
                            <td class="px-4 py-2 border">{!! Str::limit($item->sejarah, 100) !!}</td>
                            <td class="px-4 py-2 border">{{ $item->visi }}</td>
                            <td class="px-4 py-2 border">{{ $item->misi }}</td>
                            <td class="px-4 py-2 border">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($item->photos as $photo)
                                        <div class="relative group">
                                            <img src="{{ asset($photo->photo_path) }}"
                                                class="w-24 h-24 object-cover object-center rounded shadow-md aspect-square">
                                            <button onclick="confirmDeletePhoto({{ $photo->id }})"
                                                class="absolute top-1 right-1 z-10 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 text-xs font-bold leading-none hidden group-hover:block">
                                                ×
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-2 border space-x-1">
                                <button onclick="openEditModal({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>
                                <button onclick="confirmDelete({{ $item->id }})"
                                    class="text-red-600 hover:text-red-800 px-2 py-1 text-xs border border-red-300 rounded hover:bg-red-50">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <form id="deletePhotoForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah About</h2>
            <form action="{{ route('about.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Sejarah</label>
                        <textarea name="sejarah" id="editorAddSejarah" rows="4" class="w-full border rounded p-2 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Visi</label>
                        <input type="text" name="visi" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Misi</label>
                        <input type="text" name="misi" required class="w-full border rounded p-2 text-sm" />
                    </div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Upload Foto</label>
                    <div id="fileInputsContainer">
                        <div class="relative mb-4">
                            <input type="file" name="photos[]" id="addImageInput1"
                                onchange="previewImage(this, 'addPreview1')" accept="image/png,image/jpg,image/jpeg"
                                class="hidden" />

                            <div id="addUploadArea1" onclick="document.getElementById('addImageInput1').click()"
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

                            <div id="addPreview1" class="mt-4"></div>
                        </div>
                    </div>
                    <button type="button" onclick="addMoreFileInput()"
                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                        + Tambah Foto Lain
                    </button>
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
            <h2 class="text-lg font-semibold">Edit About</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <input type="hidden" name="photo_ids_to_delete" id="photoIdsToDelete">

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Sejarah</label>
                        <textarea name="sejarah" id="editorEditSejarah" rows="4" class="w-full border rounded p-2 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Visi</label>
                        <input type="text" name="visi" id="editVisi" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Misi</label>
                        <input type="text" name="misi" id="editMisi" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                </div>

                <!-- Existing Photos Section -->
                <div class="mt-4" id="existingPhotosSection" style="display: none;">
                    <label class="block mb-2 font-medium">Foto Lama</label>
                    <div class="flex flex-wrap gap-2 mb-4" id="existingPhotosContainer"></div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Tambah Foto Baru</label>
                    <div id="editFileInputsContainer">
                        <div class="relative mb-4">
                            <input type="file" name="photos[]" id="editImageInput1"
                                onchange="previewImage(this, 'editPreview1')" accept="image/png,image/jpg,image/jpeg"
                                class="hidden" />

                            <div id="editUploadArea1" onclick="document.getElementById('editImageInput1').click()"
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

                            <div id="editPreview1" class="mt-4"></div>
                        </div>
                    </div>
                    <button type="button" onclick="addMoreEditFileInput()"
                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                        + Tambah Foto Lain
                    </button>
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
        window.abouts = @json($abouts);
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <script>
        let fileInputCounter = 1;
        let editFileInputCounter = 1;
        const photoIdsToDelete = [];

        // Global variables for CKEditor instances
        let addSejarahEditor = null;
        let editSejarahEditor = null;

        // Initialize CKEditor when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor for Add Modal
            ClassicEditor
                .create(document.querySelector('#editorAddSejarah'))
                .then(editor => {
                    addSejarahEditor = editor;
                })
                .catch(error => {
                    console.error('Error initializing add editor:', error);
                });

            // Initialize CKEditor for Edit Modal
            ClassicEditor
                .create(document.querySelector('#editorEditSejarah'))
                .then(editor => {
                    editSejarahEditor = editor;
                })
                .catch(error => {
                    console.error('Error initializing edit editor:', error);
                });

            // Setup drag and drop
            setupDragAndDrop();
        });

        // Add more file input for add modal
        function addMoreFileInput() {
            fileInputCounter++;
            const container = document.getElementById('fileInputsContainer');
            const inputWrapper = document.createElement('div');
            inputWrapper.className = 'relative mb-4';
            inputWrapper.innerHTML = `
                <input type="file" name="photos[]" id="addImageInput${fileInputCounter}" onchange="previewImage(this, 'addPreview${fileInputCounter}')"
                    accept="image/png,image/jpg,image/jpeg" class="hidden" />

                <div id="addUploadArea${fileInputCounter}" onclick="document.getElementById('addImageInput${fileInputCounter}').click()"
                     class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-600 mb-2">Klik untuk upload atau drag and drop</p>
                        <p class="text-sm text-gray-500">PNG, JPG, atau GIF (MAX. 2MB)</p>
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="mt-2 bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                            Hapus
                        </button>
                    </div>
                </div>

                <div id="addPreview${fileInputCounter}" class="mt-4"></div>
            `;
            container.appendChild(inputWrapper);
            setupDragAndDropForElement(`addUploadArea${fileInputCounter}`, `addImageInput${fileInputCounter}`);
        }

        // Add more file input for edit modal
        function addMoreEditFileInput() {
            editFileInputCounter++;
            const container = document.getElementById('editFileInputsContainer');
            const inputWrapper = document.createElement('div');
            inputWrapper.className = 'relative mb-4';
            inputWrapper.innerHTML = `
                <input type="file" name="photos[]" id="editImageInput${editFileInputCounter}" onchange="previewImage(this, 'editPreview${editFileInputCounter}')"
                    accept="image/png,image/jpg,image/jpeg" class="hidden" />

                <div id="editUploadArea${editFileInputCounter}" onclick="document.getElementById('editImageInput${editFileInputCounter}').click()"
                     class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        <p class="text-gray-600 mb-2">Klik untuk upload atau drag and drop</p>
                        <p class="text-sm text-gray-500">PNG, JPG, atau GIF (MAX. 2MB)</p>
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="mt-2 bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                            Hapus
                        </button>
                    </div>
                </div>

                <div id="editPreview${editFileInputCounter}" class="mt-4"></div>
            `;
            container.appendChild(inputWrapper);
            setupDragAndDropForElement(`editUploadArea${editFileInputCounter}`, `editImageInput${editFileInputCounter}`);
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
            // Reset form
            document.querySelector('#addModal form').reset();

            // Reset CKEditor content
            if (addSejarahEditor) {
                addSejarahEditor.setData('');
            }

            // Reset file inputs
            fileInputCounter = 1;
            document.getElementById('fileInputsContainer').innerHTML = `
                <div class="relative mb-4">
                    <input type="file" name="photos[]" id="addImageInput1" onchange="previewImage(this, 'addPreview1')"
                        accept="image/png,image/jpg,image/jpeg" class="hidden" />

                    <div id="addUploadArea1" onclick="document.getElementById('addImageInput1').click()"
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

                    <div id="addPreview1" class="mt-4"></div>
                </div>
            `;

            // Show modal
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id) {
            const aboutData = window.abouts?.find(about => about.id == id);

            if (!aboutData) {
                Swal.fire('Error', 'Data about tidak ditemukan', 'error');
                return;
            }

            // Set form action
            const form = document.getElementById('editForm');
            form.action = `/about/${aboutData.id}`;

            // Populate form fields
            document.getElementById('editId').value = aboutData.id || '';
            document.getElementById('editVisi').value = aboutData.visi || '';
            document.getElementById('editMisi').value = aboutData.misi || '';

            // Set CKEditor content
            if (editSejarahEditor) {
                editSejarahEditor.setData(aboutData.sejarah || '');
            }

            // Reset photo deletion array
            photoIdsToDelete.length = 0;
            document.getElementById('photoIdsToDelete').value = '';

            // Handle existing photos
            const existingPhotosContainer = document.getElementById('existingPhotosContainer');
            const existingPhotosSection = document.getElementById('existingPhotosSection');

            existingPhotosContainer.innerHTML = '';
            if (aboutData.photos && aboutData.photos.length > 0) {
                aboutData.photos.forEach(photo => {
                    const photoDiv = document.createElement('div');
                    photoDiv.className = 'relative group w-24 h-24 overflow-hidden rounded shadow-md';
                    photoDiv.setAttribute('data-photo-id', photo.id);
                    photoDiv.innerHTML = `
                        <img src="/${photo.photo_path}" class="w-full h-full object-cover object-center rounded shadow-md">
                        <button type="button" onclick="removeExistingPhoto(${photo.id}, this)"
                            class="absolute -top-1 -right-1 bg-red-600 hover:bg-red-700 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            ×
                        </button>
                    `;
                    existingPhotosContainer.appendChild(photoDiv);
                });
                existingPhotosSection.style.display = 'block';
            } else {
                existingPhotosSection.style.display = 'none';
            }

            // Reset new file inputs
            editFileInputCounter = 1;
            document.getElementById('editFileInputsContainer').innerHTML = `
                <div class="relative mb-4">
                    <input type="file" name="photos[]" id="editImageInput1" onchange="previewImage(this, 'editPreview1')"
                        accept="image/png,image/jpg,image/jpeg" class="hidden" />

                    <div id="editUploadArea1" onclick="document.getElementById('editImageInput1').click()"
                         class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-gray-600 mb-2">Klik untuk upload atau drag and drop</p>
                            <p class="text-sm text-gray-500">PNG, JPG, atau GIF (MAX. 2MB) - Opsional</p>
                        </div>
                    </div>

                    <div id="editPreview1" class="mt-4"></div>
                </div>
            `;

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function removeExistingPhoto(photoId, button) {
            Swal.fire({
                title: 'Hapus Foto?',
                text: "Foto ini akan dihapus dari data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const photoIdStr = photoId.toString();

                    if (!photoIdsToDelete.includes(photoIdStr)) {
                        photoIdsToDelete.push(photoIdStr);
                    }

                    document.getElementById('photoIdsToDelete').value = photoIdsToDelete.join(',');
                    button.parentElement.remove();

                    const container = document.getElementById('existingPhotosContainer');
                    if (container.children.length === 0) {
                        document.getElementById('existingPhotosSection').style.display = 'none';
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Foto berhasil dihapus dari preview',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Confirm Delete About Data
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
                    form.action = `/about/${id}`;
                    form.submit();
                }
            });
        }

        // Confirm Delete Photo (from table view)
        function confirmDeletePhoto(photoId) {
            Swal.fire({
                title: 'Hapus Foto?',
                text: "Foto ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deletePhotoForm');
                    form.action = `/about/photo/${photoId}`;
                    form.submit();
                }
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

        // Setup drag and drop functionality
        function setupDragAndDrop() {
            setupDragAndDropForElement('addUploadArea1', 'addImageInput1');
            setupDragAndDropForElement('editUploadArea1', 'editImageInput1');
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
