@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Data Anggota</h1>
            <div class="flex gap-2">
                <button id="bulkDeleteBtn" onclick="bulkDelete()"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>
                    Hapus Terpilih
                </button>
                <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Tambah Anggota
                </button>
            </div>
        </div>

        <!-- Search Input -->
        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama atau jabatan..."
                class="border px-3 py-2 w-full rounded text-sm" onkeyup="searchTable()">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border"><input type="checkbox" id="selectAll"></th>
                        <th class="px-4 py-2 border">Foto</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Jabatan</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="anggotaTable">
                    @foreach ($anggotas as $item)
                        <tr>
                            <td class="px-4 py-2 border">
                                <input type="checkbox" name="anggota_ids[]" value="{{ $item->id }}" class="rowCheckbox"
                                    onchange="updateBulkDeleteButton()">
                            </td>
                            <td class="px-4 py-2 border">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                        class="w-24 h-24 object-cover object-center rounded shadow-md aspect-square">
                                @else
                                    <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-2 border font-medium">{{ $item->name }}</td>
                            <td class="px-4 py-2 border">{{ $item->title ?? '-' }}</td>
                            <td class="px-4 py-2 border space-x-1">
                                <button onclick="openEditModal(this)" data-item='@json($item)'
                                    class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form id="bulkDeleteForm" method="POST" action="{{ route('anggota.bulkDelete') }}">
        @csrf
    </form>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah Anggota</h2>
            <form id="addForm" action="{{ route('anggota.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Email</label>
                        <input type="email" name="email" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">No. Telepon</label>
                        <input type="text" name="phone_number" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_anggota_id" required class="w-full border rounded p-2 text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Facebook ID</label>
                        <input type="text" name="facebook_id" class="w-full border rounded p-2 text-sm"
                            placeholder="@username atau link profil" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Instagram ID</label>
                        <input type="text" name="instagram_id" class="w-full border rounded p-2 text-sm"
                            placeholder="@username atau link profil" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">TikTok ID</label>
                        <input type="text" name="tiktok_id" class="w-full border rounded p-2 text-sm"
                            placeholder="@username atau link profil" />
                    </div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Upload Foto <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="file" name="image" id="addImageInput" onchange="previewImage(this, 'addPreview')"
                            accept="image/png,image/jpg,image/jpeg" class="hidden" required />

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
                                <p class="text-sm text-gray-500">PNG, JPG, atau GIF (MAX. 2MB) - Wajib</p>
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
            <h2 class="text-lg font-semibold">Edit Anggota</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="editName" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="editTitle" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Email</label>
                        <input type="email" name="email" id="editEmail" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">No. Telepon</label>
                        <input type="text" name="phone_number" id="editPhoneNumber"
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_anggota_id" id="editCategory" required
                            class="w-full border rounded p-2 text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Facebook ID</label>
                        <input type="text" name="facebook_id" id="editFacebookId"
                            class="w-full border rounded p-2 text-sm" placeholder="@username atau link profil" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Instagram ID</label>
                        <input type="text" name="instagram_id" id="editInstagramId"
                            class="w-full border rounded p-2 text-sm" placeholder="@username atau link profil" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">TikTok ID</label>
                        <input type="text" name="tiktok_id" id="editTiktokId"
                            class="w-full border rounded p-2 text-sm" placeholder="@username atau link profil" />
                    </div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Ganti Foto</label>
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
                                <p class="text-sm text-gray-500">PNG, JPG, atau GIF (MAX. 2MB) - Opsional</p>
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Show flash messages using SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showSuccessAlert("{{ session('success') }}");
            @endif

            @if(session('error'))
                showErrorAlert("{{ session('error') }}");
            @endif

            @if($errors->any())
                let errorMessages = '';
                @foreach($errors->all() as $error)
                    errorMessages += '{{ $error }}\n';
                @endforeach
                showErrorAlert(errorMessages);
            @endif

            setupDragAndDrop();

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
        });

        // SweetAlert helper functions
        function showSuccessAlert(message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                html: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        }

        function showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: message.replace(/\n/g, '<br>'),
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        }

        function showLoadingAlert(message = 'Memproses...') {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Search functionality
        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#anggotaTable tr");

            rows.forEach(row => {
                let name = row.cells[2]?.textContent?.toLowerCase() || '';
                let title = row.cells[3]?.textContent?.toLowerCase() || '';

                const shouldShow = name.includes(input) || title.includes(input);
                row.style.display = shouldShow ? "" : "none";
            });
        }

        // Debounce search input
        let searchTimer;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(searchTable, 300);
        });

        // Bulk Delete Function with Enhanced SweetAlert
        function bulkDelete() {
            const checkedBoxes = document.querySelectorAll('.rowCheckbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            if (ids.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pilih minimal satu anggota untuk dihapus!',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            Swal.fire({
                title: `Hapus ${ids.length} anggota terpilih?`,
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
                    showLoadingAlert('Menghapus anggota...');

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

        // Update Bulk Delete Button
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

        // Modal Functions
        function openAddModal() {
            // Reset form
            document.getElementById('addForm').reset();
            document.getElementById('addPreview').innerHTML = '';
            document.getElementById('addUploadArea').style.display = 'block';

            // Show modal
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(button) {
            const data = JSON.parse(button.getAttribute('data-item'));
            const form = document.getElementById('editForm');

            form.action = `/anggota/${data.id}`;
            document.getElementById('editName').value = data.name || '';
            document.getElementById('editTitle').value = data.title || '';
            document.getElementById('editEmail').value = data.email || '';
            document.getElementById('editPhoneNumber').value = data.phone_number || '';
            document.getElementById('editCategory').value = data.category_anggota_id || '';
            document.getElementById('editFacebookId').value = data.facebook_id || '';
            document.getElementById('editInstagramId').value = data.instagram_id || '';
            document.getElementById('editTiktokId').value = data.tiktok_id || '';

            // Handle image preview
            const editPreview = document.getElementById('editPreview');
            const editUploadArea = document.getElementById('editUploadArea');

            if (data.image) {
                editPreview.innerHTML = `
                    <div class="relative inline-block">
                        <img src="/storage/${data.image}" class="h-32 w-32 rounded-lg shadow-md object-cover border" alt="Current image">
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
                    showErrorAlert('File harus berupa gambar (PNG/JPG/JPEG)!');
                    input.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showErrorAlert('Ukuran file maksimal 2MB!');
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

        // Enhanced Form Submissions with SweetAlert - FIXED VERSION
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Add Form
            document.getElementById('addForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = document.getElementById('addSubmitBtn');
                const originalText = submitBtn.textContent;

                // Validate required fields
                if (!formData.get('name') || !formData.get('title') || !formData.get('category_anggota_id')) {
                    showErrorAlert('Harap isi semua field yang wajib diisi!');
                    return;
                }

                // Validate image for add form
                if (!formData.get('image') || !formData.get('image').size) {
                    showErrorAlert('Foto wajib diupload!');
                    return;
                }

                submitBtn.textContent = 'Menyimpan...';
                submitBtn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    // Handle both JSON and redirect responses
                    const contentType = response.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => ({
                            success: response.ok,
                            data: data,
                            status: response.status
                        }));
                    } else {
                        // If it's a redirect (typical Laravel behavior after successful form submission)
                        if (response.ok || response.redirected) {
                            return {
                                success: true,
                                data: { message: 'Anggota berhasil ditambahkan!' },
                                status: response.status
                            };
                        } else {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                    }
                })
                .then(result => {
                    if (result.success) {
                        closeAddModal();
                        showSuccessAlert(result.data.message || 'Anggota berhasil ditambahkan!');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        // Handle validation errors
                        if (result.data.errors) {
                            let errorMessage = '';
                            Object.values(result.data.errors).forEach(errorArray => {
                                errorArray.forEach(error => {
                                    errorMessage += error + '<br>';
                                });
                            });
                            showErrorAlert(errorMessage);
                        } else {
                            showErrorAlert(result.data.message || 'Gagal menambahkan anggota!');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorAlert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
                })
                .finally(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
            });

            // Handle Edit Form
            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = document.getElementById('editSubmitBtn');
                const originalText = submitBtn.textContent;

                // Validate required fields
                if (!formData.get('name') || !formData.get('title') || !formData.get('category_anggota_id')) {
                    showErrorAlert('Harap isi semua field yang wajib diisi!');
                    return;
                }

                submitBtn.textContent = 'Menyimpan...';
                submitBtn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    // Handle both JSON and redirect responses
                    const contentType = response.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => ({
                            success: response.ok,
                            data: data,
                            status: response.status
                        }));
                    } else {
                        // If it's a redirect (typical Laravel behavior after successful form submission)
                        if (response.ok || response.redirected) {
                            return {
                                success: true,
                                data: { message: 'Anggota berhasil diperbarui!' },
                                status: response.status
                            };
                        } else {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                    }
                })
                .then(result => {
                    if (result.success) {
                        closeEditModal();
                        showSuccessAlert(result.data.message || 'Anggota berhasil diperbarui!');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        // Handle validation errors
                        if (result.data.errors) {
                            let errorMessage = '';
                            Object.values(result.data.errors).forEach(errorArray => {
                                errorArray.forEach(error => {
                                    errorMessage += error + '<br>';
                                });
                            });
                            showErrorAlert(errorMessage);
                        } else {
                            showErrorAlert(result.data.message || 'Gagal memperbarui anggota!');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorAlert('Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
                })
                .finally(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
            });
        });
    </script>
@endsection
