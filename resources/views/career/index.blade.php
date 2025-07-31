@extends('layouts.app')

@section('content')
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Loker</h1>
            <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Tambah Career
            </button>
        </div>

        <div class="mb-4">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan posisi..."
                class="border px-3 py-2 w-full rounded text-sm" onkeyup="searchTable()">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border">Job Type</th>
                        <th class="px-4 py-2 border">Position</th>
                        <th class="px-4 py-2 border">Description</th>
                        <th class="px-4 py-2 border" width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody id="careerTable">
                    @foreach ($careers as $index => $career)
                        <tr>
                            <td class="px-4 py-2 border">{{ $career->job_type }}</td>
                            <td class="px-4 py-2 border">{{ $career->position_title }}</td>
                            <td class="px-4 py-2 border">
                                {{ Str::limit(implode(' ', is_array($career->deskripsi) ? $career->deskripsi : [$career->deskripsi]), 60) }}
                            </td>
                            <td class="px-4 py-2 border">
                                <div class="flex justify-end space-x-1">
                                    <a href="{{ route('career.show', $career->id) }}"
                                        class="text-green-600 hover:text-green-800 px-2 py-1 text-xs border border-green-300 rounded hover:bg-green-50 inline-block">Detail</a>
                                    <button onclick="openEditModal({{ $career->id }})"
                                        class="text-blue-600 hover:text-blue-800 px-2 py-1 text-xs border border-blue-300 rounded hover:bg-blue-50">Edit</button>
                                    <button onclick="confirmDelete({{ $career->id }})" data-id="{{ $career->id }}"
                                        class="text-red-600 hover:text-red-800 px-2 py-1 text-xs border border-red-300 rounded hover:bg-red-50 delete-btn">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.careers = @json($careers);
    </script>

    <!-- Form tersembunyi untuk delete -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl p-6 space-y-4 overflow-y-auto max-h-screen">
            <h2 class="text-lg font-semibold">Tambah Career</h2>
            <form action="{{ route('career.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Job Type</label>
                        <select name="job_type" required class="w-full border rounded p-2 text-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                            <option value="Contract">Contract</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Position Title</label>
                        <input type="text" name="position_title" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Lokasi</label>
                        <input type="text" name="lokasi" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Pengalaman</label>
                        <input type="text" name="pengalaman" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Jam Kerja</label>
                        <input type="text" name="jam_kerja" required class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Hari Kerja</label>
                        <input type="text" name="hari_kerja" required class="w-full border rounded p-2 text-sm" />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block mb-1 font-medium">Ringkasan</label>
                    <textarea name="ringkasan" id="editorAddRingkasan" rows="4" required class="w-full border rounded p-2 text-sm"></textarea>
                </div>

                <div class="mt-4">
                    <label class="block mb-1 font-medium">Klasifikasi</label>
                    <div id="addKlasifikasiContainer" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="klasifikasi[]" required class="w-full border rounded p-2 text-sm" />
                        </div>
                    </div>
                    <button type="button" onclick="addInput('addKlasifikasiContainer', 'klasifikasi[]')"
                        class="text-sm text-blue-600 mt-2 hover:text-blue-800">+ Tambah Klasifikasi</button>
                </div>

                <div class="mt-4">
                    <label class="block mb-1 font-medium">Deskripsi</label>
                    <div id="addDeskripsiContainer" class="space-y-2">
                        <div class="flex gap-2">
                            <textarea name="deskripsi[]" id="editorAddDeskripsi0" rows="4" required class="w-full border rounded p-2 text-sm ckeditor-textarea"></textarea>
                        </div>
                    </div>
                    <button type="button" onclick="addTextarea('addDeskripsiContainer', 'deskripsi[]')"
                        class="text-sm text-blue-600 mt-2 hover:text-blue-800">+ Tambah Deskripsi</button>
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
            <h2 class="text-lg font-semibold">Edit Career</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Job Type</label>
                        <select name="job_type" id="editJobType" required class="w-full border rounded p-2 text-sm">
                            <option value="">Pilih</option>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                            <option value="Contract">Contract</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Position Title</label>
                        <input type="text" name="position_title" id="editPositionTitle" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Lokasi</label>
                        <input type="text" name="lokasi" id="editLokasi" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Pengalaman</label>
                        <input type="text" name="pengalaman" id="editPengalaman" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Jam Kerja</label>
                        <input type="text" name="jam_kerja" id="editJamKerja" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Hari Kerja</label>
                        <input type="text" name="hari_kerja" id="editHariKerja" required
                            class="w-full border rounded p-2 text-sm" />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block mb-1 font-medium">Ringkasan</label>
                    <textarea name="ringkasan" id="editorEditRingkasan" rows="4" required class="w-full border rounded p-2 text-sm"></textarea>
                </div>

                <div class="mt-4">
                    <label class="block mb-1 font-medium">Klasifikasi</label>
                    <div id="editKlasifikasiContainer" class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" name="klasifikasi[]" required
                                class="w-full border rounded p-2 text-sm" />
                        </div>
                    </div>
                    <button type="button" onclick="addInput('editKlasifikasiContainer', 'klasifikasi[]')"
                        class="text-sm text-blue-600 mt-2 hover:text-blue-800">+ Tambah Klasifikasi</button>
                </div>

                <div class="mt-4">
                    <label class="block mb-1 font-medium">Deskripsi</label>
                    <div id="editDeskripsiContainer" class="space-y-2">
                        <div class="flex gap-2">
                            <textarea name="deskripsi[]" id="editorEditDeskripsi0" rows="4" required class="w-full border rounded p-2 text-sm ckeditor-textarea"></textarea>
                        </div>
                    </div>
                    <button type="button" onclick="addTextarea('editDeskripsiContainer', 'deskripsi[]')"
                        class="text-sm text-blue-600 mt-2 hover:text-blue-800">+ Tambah Deskripsi</button>
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
        let addRingkasanEditor = null;
        let editRingkasanEditor = null;
        let addDeskripsiEditors = [];
        let editDeskripsiEditors = [];
        let editorCounter = 0;

        // Initialize CKEditor when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor for Add Modal - Ringkasan
            ClassicEditor
                .create(document.querySelector('#editorAddRingkasan'))
                .then(editor => {
                    addRingkasanEditor = editor;
                })
                .catch(error => {
                    console.error('Error initializing add ringkasan editor:', error);
                });

            // Initialize CKEditor for Edit Modal - Ringkasan
            ClassicEditor
                .create(document.querySelector('#editorEditRingkasan'))
                .then(editor => {
                    editRingkasanEditor = editor;
                })
                .catch(error => {
                    console.error('Error initializing edit ringkasan editor:', error);
                });

            // Initialize CKEditor for Add Modal - First Deskripsi
            ClassicEditor
                .create(document.querySelector('#editorAddDeskripsi0'))
                .then(editor => {
                    addDeskripsiEditors[0] = editor;
                })
                .catch(error => {
                    console.error('Error initializing add deskripsi editor:', error);
                });

            // Initialize CKEditor for Edit Modal - First Deskripsi
            ClassicEditor
                .create(document.querySelector('#editorEditDeskripsi0'))
                .then(editor => {
                    editDeskripsiEditors[0] = editor;
                })
                .catch(error => {
                    console.error('Error initializing edit deskripsi editor:', error);
                });
        });

        function openAddModal() {
            // Reset form
            document.querySelector('#addModal form').reset();

            // Reset CKEditor content
            if (addRingkasanEditor) {
                addRingkasanEditor.setData('');
            }

            // Reset and destroy additional deskripsi editors
            addDeskripsiEditors.forEach((editor, index) => {
                if (index > 0 && editor) {
                    editor.destroy();
                }
            });
            addDeskripsiEditors = addDeskripsiEditors.slice(0, 1);

            // Reset first deskripsi editor
            if (addDeskripsiEditors[0]) {
                addDeskripsiEditors[0].setData('');
            }

            // Reset dynamic fields
            document.getElementById('addKlasifikasiContainer').innerHTML =
                '<div class="flex gap-2"><input type="text" name="klasifikasi[]" required class="w-full border rounded p-2 text-sm" /></div>';
            document.getElementById('addDeskripsiContainer').innerHTML =
                '<div class="flex gap-2"><textarea name="deskripsi[]" id="editorAddDeskripsi0" rows="4" required class="w-full border rounded p-2 text-sm ckeditor-textarea"></textarea></div>';

            // Reinitialize first deskripsi editor
            setTimeout(() => {
                ClassicEditor
                    .create(document.querySelector('#editorAddDeskripsi0'))
                    .then(editor => {
                        addDeskripsiEditors[0] = editor;
                    })
                    .catch(error => {
                        console.error('Error reinitializing add deskripsi editor:', error);
                    });
            }, 100);

            // Show modal
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id) {
            const careerData = window.careers?.find(career => career.id == id);

            if (!careerData) {
                Swal.fire('Error', 'Data career tidak ditemukan', 'error');
                return;
            }

            // Set form action dengan route yang benar
            const form = document.getElementById('editForm');
            form.action = `/career/${careerData.id}`;

            // Populate form fields dengan handling null values
            document.getElementById('editId').value = careerData.id || '';
            document.getElementById('editJobType').value = careerData.job_type || '';
            document.getElementById('editPositionTitle').value = careerData.position_title || '';
            document.getElementById('editLokasi').value = careerData.lokasi || '';
            document.getElementById('editPengalaman').value = careerData.pengalaman || '';
            document.getElementById('editJamKerja').value = careerData.jam_kerja || '';
            document.getElementById('editHariKerja').value = careerData.hari_kerja || '';

            // Set ringkasan content in CKEditor
            if (editRingkasanEditor) {
                editRingkasanEditor.setData(careerData.ringkasan || '');
            }

            // Handle klasifikasi array
            let klasifikasiHTML = '';
            try {
                if (careerData.klasifikasi && Array.isArray(careerData.klasifikasi) && careerData.klasifikasi.length > 0) {
                    careerData.klasifikasi.forEach((k, index) => {
                        klasifikasiHTML += `<div class="flex gap-2">
                    <input type="text" name="klasifikasi[]" value="${escapeHtml(k || '')}" required class="w-full border rounded p-2 text-sm" />
                    ${index > 0 ? '<button type="button" onclick="removeField(this)" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">×</button>' : ''}
                </div>`;
                    });
                } else {
                    klasifikasiHTML =
                        '<div class="flex gap-2"><input type="text" name="klasifikasi[]" required class="w-full border rounded p-2 text-sm" /></div>';
                }
            } catch (error) {
                console.error('Error handling klasifikasi:', error);
                klasifikasiHTML =
                    '<div class="flex gap-2"><input type="text" name="klasifikasi[]" required class="w-full border rounded p-2 text-sm" /></div>';
            }
            document.getElementById('editKlasifikasiContainer').innerHTML = klasifikasiHTML;

            // Destroy existing deskripsi editors
            editDeskripsiEditors.forEach((editor, index) => {
                if (editor) {
                    editor.destroy();
                }
            });
            editDeskripsiEditors = [];

            // Handle deskripsi array with CKEditor
            let deskripsiHTML = '';
            try {
                if (careerData.deskripsi && Array.isArray(careerData.deskripsi) && careerData.deskripsi.length > 0) {
                    careerData.deskripsi.forEach((d, index) => {
                        deskripsiHTML += `<div class="flex gap-2">
                    <textarea name="deskripsi[]" id="editorEditDeskripsi${index}" rows="4" required class="w-full border rounded p-2 text-sm ckeditor-textarea">${escapeHtml(d || '')}</textarea>
                    ${index > 0 ? '<button type="button" onclick="removeDeskripsiField(this, ' + index + ')" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm self-start">×</button>' : ''}
                </div>`;
                    });
                } else {
                    deskripsiHTML =
                        '<div class="flex gap-2"><textarea name="deskripsi[]" id="editorEditDeskripsi0" rows="4" required class="w-full border rounded p-2 text-sm ckeditor-textarea"></textarea></div>';
                }
            } catch (error) {
                console.error('Error handling deskripsi:', error);
                deskripsiHTML =
                    '<div class="flex gap-2"><textarea name="deskripsi[]" id="editorEditDeskripsi0" rows="4" required class="w-full border rounded p-2 text-sm ckeditor-textarea"></textarea></div>';
            }
            document.getElementById('editDeskripsiContainer').innerHTML = deskripsiHTML;

            // Initialize CKEditor for each deskripsi textarea
            setTimeout(() => {
                const textareas = document.querySelectorAll('#editDeskripsiContainer .ckeditor-textarea');
                textareas.forEach((textarea, index) => {
                    ClassicEditor
                        .create(textarea)
                        .then(editor => {
                            editDeskripsiEditors[index] = editor;
                            if (careerData.deskripsi && careerData.deskripsi[index]) {
                                editor.setData(careerData.deskripsi[index]);
                            }
                        })
                        .catch(error => {
                            console.error(`Error initializing edit deskripsi editor ${index}:`, error);
                        });
                });
            }, 100);

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function addInput(containerId, name) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'flex gap-2';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = name;
            input.className = 'w-full border rounded p-2 text-sm';
            input.required = true;

            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm';
            deleteBtn.innerHTML = '×';
            deleteBtn.onclick = function() {
                removeField(this);
            };

            div.appendChild(input);
            div.appendChild(deleteBtn);
            container.appendChild(div);
        }

        function addTextarea(containerId, name) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'flex gap-2';

            const textarea = document.createElement('textarea');
            textarea.name = name;
            textarea.rows = 4;
            textarea.className = 'w-full border rounded p-2 text-sm ckeditor-textarea';
            textarea.required = true;

            // Generate unique ID for CKEditor
            editorCounter++;
            const editorId = containerId.includes('add') ? `editorAddDeskripsi${editorCounter}` : `editorEditDeskripsi${editorCounter}`;
            textarea.id = editorId;

            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm self-start';
            deleteBtn.innerHTML = '×';
            deleteBtn.onclick = function() {
                removeDeskripsiField(this, editorCounter);
            };

            div.appendChild(textarea);
            div.appendChild(deleteBtn);
            container.appendChild(div);

            // Initialize CKEditor for the new textarea
            setTimeout(() => {
                ClassicEditor
                    .create(document.querySelector(`#${editorId}`))
                    .then(editor => {
                        if (containerId.includes('add')) {
                            addDeskripsiEditors[editorCounter] = editor;
                        } else {
                            editDeskripsiEditors[editorCounter] = editor;
                        }
                    })
                    .catch(error => {
                        console.error(`Error initializing editor ${editorId}:`, error);
                    });
            }, 100);
        }

        function removeField(button) {
            const container = button.parentElement.parentElement;

            if (container.children.length > 1) {
                button.parentElement.remove();
            } else {
                Swal.fire('Peringatan', 'Minimal harus ada satu field!', 'warning');
            }
        }

        function removeDeskripsiField(button, editorIndex) {
            const container = button.parentElement.parentElement;

            if (container.children.length > 1) {
                // Destroy the CKEditor instance
                const isAdd = container.id.includes('add');
                const editorArray = isAdd ? addDeskripsiEditors : editDeskripsiEditors;

                if (editorArray[editorIndex]) {
                    editorArray[editorIndex].destroy();
                    delete editorArray[editorIndex];
                }

                button.parentElement.remove();
            } else {
                Swal.fire('Peringatan', 'Minimal harus ada satu field deskripsi!', 'warning');
            }
        }

        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#careerTable tr");
            rows.forEach(row => {
                let position = row.cells[1]?.textContent?.toLowerCase() || '';
                let jobType = row.cells[0]?.textContent?.toLowerCase() || '';
                let description = row.cells[2]?.textContent?.toLowerCase() || '';

                const shouldShow = position.includes(input) || jobType.includes(input) || description.includes(
                    input);
                row.style.display = shouldShow ? "" : "none";
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Career?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/career/${id}`;
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
    </script>
@endsection
