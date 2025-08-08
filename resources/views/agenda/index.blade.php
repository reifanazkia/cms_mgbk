@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Agenda</h1>
            <div class="flex gap-2">
                <button id="bulkDeleteBtn" onclick="bulkDelete()"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>
                    Hapus Terpilih
                </button>
                <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Tambah Agenda
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
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Lokasi</th>
                        <th class="px-4 py-2 border">Pembicara</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="agendaTable">
                    @foreach ($agendas as $item)
                        <tr>
                            <td class="px-4 py-2 border">
                                <input type="checkbox" name="agenda_ids[]" value="{{ $item->id }}" class="rowCheckbox"
                                    onchange="updateBulkDeleteButton()">
                            </td>
                            <td class="px-4 py-2 border">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                        class="w-24 h-24 object-cover object-center rounded shadow-md aspect-square">
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $item->title }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($item->start_datetime)->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2 border">{{ $item->location }}</td>
                            <td class="px-4 py-2 border">
                                @if ($item->speakers && $item->speakers->count() > 0)
                                    @foreach ($item->speakers as $speaker)
                                        <span
                                            class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">
                                            {{ $speaker->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-gray-400">Tidak ada pembicara</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $item->status }}</td>
                            <td class="px-4 py-2 border space-x-1">
                                <a href="{{ route('agenda.show', $item->id) }}"
                                    class="text-green-600 hover:text-green-800 px-2 py-1 text-xs border border-green-300 rounded hover:bg-green-50 inline-block">Detail</a>
                                <button onclick="openEditModal({{ $item->id }})"
                                    class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.agendas = @json($agendas);
    </script>

    <form id="bulkDeleteForm" method="POST" action="{{ route('agenda.bulkDelete') }}">
        @csrf
    </form>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah Agenda</h2>
            <form action="{{ route('agenda.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Judul</label>
                        <input type="text" name="title" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Penyelenggara</label>
                        <input type="text" name="event_organizer" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tanggal Mulai</label>
                        <input type="datetime-local" name="start_datetime" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tanggal Selesai</label>
                        <input type="datetime-local" name="end_datetime" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Lokasi</label>
                        <input type="text" name="location" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Deskripsi</label>
                        <textarea name="description" id="editorAddDescription" rows="4" class="w-full border rounded p-2 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tautan Registrasi</label>
                        <input type="url" name="register_link" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tautan YouTube</label>
                        <input type="url" name="youtube_link" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tipe</label>
                        <input type="text" name="type" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status" class="w-full border rounded p-2 text-sm">
                            <option value="Open">Open</option>
                            <option value="Soldout">Soldout</option>
                        </select>
                    </div>
                </div>

                <!-- Enhanced Image Upload Section -->
                <div class="mt-4">
                    <label class="block mb-2 font-medium">Upload Gambar</label>
                    <div class="relative">
                        <input type="file" name="image" id="addImageInput" onchange="previewImage(this, 'addPreview')"
                            accept="image/png,image/jpg,image/jpeg" class="hidden" />

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

                <div class="mt-4">
                    <label class="block mb-1 font-medium">Pembicara</label>
                    <select id="addSpeakers" name="speaker_ids[]" multiple="multiple" class="w-full">
                        @foreach ($speakers as $speaker)
                            <option value="{{ $speaker->id }}">{{ $speaker->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Pilih satu atau lebih pembicara</small>
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
            <h2 class="text-lg font-semibold">Edit Agenda</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Judul</label>
                        <input type="text" name="title" id="editTitle" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Penyelenggara</label>
                        <input type="text" name="event_organizer" id="editEventOrganizer"
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tanggal Mulai</label>
                        <input type="datetime-local" name="start_datetime" id="editStartDatetime" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tanggal Selesai</label>
                        <input type="datetime-local" name="end_datetime" id="editEndDatetime"
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Lokasi</label>
                        <input type="text" name="location" id="editLocation" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-1 font-medium">Deskripsi</label>
                        <textarea name="description" id="editorEditDescription" rows="4" class="w-full border rounded p-2 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tautan Registrasi</label>
                        <input type="url" name="register_link" id="editRegisterLink"
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tautan YouTube</label>
                        <input type="url" name="youtube_link" id="editYoutubeLink"
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Tipe</label>
                        <input type="text" name="type" id="editType" class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status" id="editStatus" class="w-full border rounded p-2 text-sm">
                            <option value="Open">Open</option>
                            <option value="Soldout">Soldout</option>
                        </select>
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
                                <p class="text-sm text-gray-500">PNG, JPG, atau GIF (MAX. 2MB) - Opsional</p>
                            </div>
                        </div>

                        <div id="editPreview" class="mt-4"></div>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block mb-1 font-medium">Pembicara</label>
                    <select id="editSpeakers" name="speaker_ids[]" multiple="multiple" class="w-full">
                        @foreach ($speakers as $speaker)
                            <option value="{{ $speaker->id }}">{{ $speaker->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Pilih satu atau lebih pembicara</small>
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

    <!-- jQuery dan Select2 CSS/JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

    <script>
        // Global variables for CKEditor instances
        let addDescriptionEditor = null;
        let editDescriptionEditor = null;

        // Initialize CKEditor when the page loads
        $(document).ready(function() {
            initializeSelect2();
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

        function initializeSelect2() {
            // Destroy existing instances
            if ($('#addSpeakers').hasClass("select2-hidden-accessible")) {
                $('#addSpeakers').select2('destroy');
            }
            if ($('#editSpeakers').hasClass("select2-hidden-accessible")) {
                $('#editSpeakers').select2('destroy');
            }

            // Initialize fresh instances
            $('#addSpeakers').select2({
                placeholder: "Pilih pembicara...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#addModal')
            });

            $('#editSpeakers').select2({
                placeholder: "Pilih pembicara...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#editModal')
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

        // Fungsi search untuk agenda
        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#agendaTable tr");

            rows.forEach(row => {
                let title = row.cells[2]?.textContent?.toLowerCase() || ''; // Kolom judul (indeks 2)
                let organizer = row.cells[1]?.textContent?.toLowerCase() || ''; // Kolom penyelenggara (indeks 1)

                const shouldShow = title.includes(input) || organizer.includes(input);
                row.style.display = shouldShow ? "" : "none";
            });
        }

        // Debounce untuk meningkatkan performa saat mengetik
        let searchTimer;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(searchTable, 300);
        });

        function openAddModal() {
            // Reset form
            document.querySelector('#addModal form').reset();
            document.getElementById('addPreview').innerHTML = '';

            // Show upload area and hide preview
            document.getElementById('addUploadArea').style.display = 'block';

            // Reset editor content
            if (addDescriptionEditor) {
                addDescriptionEditor.setData('');
            }

            // Reset select2
            $('#addSpeakers').val(null).trigger('change');

            // Show modal
            document.getElementById('addModal').classList.remove('hidden');

            // Reinitialize select2 if needed
            if (!$('#addSpeakers').hasClass("select2-hidden-accessible")) {
                $('#addSpeakers').select2({
                    placeholder: "Pilih pembicara...",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addModal')
                });
            }
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id) {
            const agendaData = window.agendas?.find(agenda => agenda.id == id);

            if (!agendaData) {
                Swal.fire('Error', 'Data agenda tidak ditemukan', 'error');
                return;
            }

            // Set form action
            const form = document.getElementById('editForm');
            form.action = `/agenda/${agendaData.id}`;

            // Populate form fields with handling null values
            document.getElementById('editId').value = agendaData.id || '';
            document.getElementById('editTitle').value = agendaData.title || '';
            document.getElementById('editEventOrganizer').value = agendaData.event_organizer || '';
            document.getElementById('editLocation').value = agendaData.location || '';
            document.getElementById('editRegisterLink').value = agendaData.register_link || '';
            document.getElementById('editYoutubeLink').value = agendaData.youtube_link || '';
            document.getElementById('editType').value = agendaData.type || '';
            document.getElementById('editStatus').value = agendaData.status || 'Open';

            // Set editor content
            if (editDescriptionEditor) {
                editDescriptionEditor.setData(agendaData.description || '');
            }

            // Handle datetime fields with error handling
            try {
                if (agendaData.start_datetime) {
                    const startDate = new Date(agendaData.start_datetime);
                    if (!isNaN(startDate.getTime())) {
                        document.getElementById('editStartDatetime').value = formatDateTimeLocal(startDate);
                    }
                }

                if (agendaData.end_datetime) {
                    const endDate = new Date(agendaData.end_datetime);
                    if (!isNaN(endDate.getTime())) {
                        document.getElementById('editEndDatetime').value = formatDateTimeLocal(endDate);
                    }
                }
            } catch (error) {
                console.error('Error parsing dates:', error);
            }

            // Handle image preview
            const editPreview = document.getElementById('editPreview');
            const editUploadArea = document.getElementById('editUploadArea');

            if (agendaData.image) {
                editPreview.innerHTML = `
                    <div class="relative inline-block">
                        <img src="/storage/${agendaData.image}" class="h-32 w-32 rounded-lg shadow-md object-cover border" alt="Current image">
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

            // Handle speakers selection
            if (agendaData.speakers && Array.isArray(agendaData.speakers) && agendaData.speakers.length > 0) {
                const speakerIds = agendaData.speakers.map(speaker => speaker.id.toString());
                $('#editSpeakers').val(speakerIds);
            } else {
                $('#editSpeakers').val([]);
            }

            // Trigger change to update select2
            $('#editSpeakers').trigger('change');

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

        function bulkDelete() {
            const checkedBoxes = document.querySelectorAll('.rowCheckbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            if (ids.length === 0) {
                Swal.fire('Tidak ada yang dipilih', '', 'warning');
                return;
            }

            Swal.fire({
                title: `Hapus ${ids.length} agenda terpilih?`,
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

        // Helper function to format datetime for datetime-local input
        function formatDateTimeLocal(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    </script>
@endsection
