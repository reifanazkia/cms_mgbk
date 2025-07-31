@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h2 class="text-xl font-bold mb-4">Profile Setting</h2>

        <div class="grid md:grid-cols-3 gap-6">
            {{-- Kiri --}}
            <div class="space-y-6">
                {{-- Logo --}}
                <div class="bg-white shadow rounded p-4 text-center">
                    <img src="{{ asset('img/logo.png') }}" class="w-20 mx-auto mb-2">
                    <h3 class="font-semibold">Hexagon Inc</h3>
                </div>

                {{-- Contact --}}
                <div class="bg-white shadow rounded p-4">
                    <h4 class="font-semibold mb-3">Contact</h4>

                    {{-- Phone --}}
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <i class="fas fa-phone mr-2"></i> Nomor Telpon
                                <div id="phone-display" class="text-sm text-blue-600">
                                    <a href="tel:{{ $profile->phone }}">{{ $profile->phone ?: 'Belum diisi' }}</a>
                                </div>
                            </div>
                            <button onclick="editField('phone')" class="text-blue-600">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                        <form id="phone-form" class="hidden mt-2" method="POST" action="{{ route('profile.contact.update') }}">
                            @csrf
                            <input type="hidden" name="type" value="phone">
                            <input type="text" name="phone" class="w-full text-sm border rounded px-2 py-1"
                                value="{{ $profile->phone }}" placeholder="Masukkan nomor telepon">
                            <div class="flex gap-2 mt-2">
                                <button type="submit"
                                    class="bg-blue-600 text-white text-sm px-3 py-1 rounded">Simpan</button>
                                <button type="button" onclick="cancelEdit('phone')"
                                    class="bg-red-500 text-white text-sm px-3 py-1 rounded">Batal</button>
                            </div>
                        </form>
                    </div>

                    {{-- Email --}}
                    <div>
                        <div class="flex justify-between items-center">
                            <div>
                                <i class="fas fa-envelope mr-2"></i> Email
                                <div id="email-display" class="text-sm text-blue-600">
                                    <a href="mailto:{{ $profile->email }}">{{ $profile->email ?: 'Belum diisi' }}</a>
                                </div>
                            </div>
                            <button onclick="editField('email')" class="text-blue-600">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                        <form id="email-form" class="hidden mt-2" method="POST" action="{{ route('profile.contact.update') }}">
                            @csrf
                            <input type="hidden" name="type" value="email">
                            <input type="email" name="email" class="w-full text-sm border rounded px-2 py-1"
                                value="{{ $profile->email }}" placeholder="Masukkan email">
                            <div class="flex gap-2 mt-2">
                                <button type="submit"
                                    class="bg-blue-600 text-white text-sm px-3 py-1 rounded">Simpan</button>
                                <button type="button" onclick="cancelEdit('email')"
                                    class="bg-red-500 text-white text-sm px-3 py-1 rounded">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Social Account --}}
                <div class="bg-white shadow rounded p-4">
                    <h4 class="font-semibold mb-3">Social Account</h4>

                    @php
                        $socialMedia = [
                            'instagram' => ['icon' => 'fab fa-instagram', 'name' => 'Instagram'],
                            'youtube' => ['icon' => 'fab fa-youtube', 'name' => 'YouTube'],
                            'facebook' => ['icon' => 'fab fa-facebook', 'name' => 'Facebook'],
                            'linkedin' => ['icon' => 'fab fa-linkedin', 'name' => 'LinkedIn']
                        ];
                    @endphp

                    @foreach ($socialMedia as $key => $social)
                        <div class="mb-3">
                            <div class="flex justify-between items-start">
                                <div class="w-full">
                                    <div class="flex items-center mb-1">
                                        <i class="{{ $social['icon'] }} mr-2"></i>
                                        <strong>{{ $social['name'] }}</strong>
                                    </div>

                                    <div id="social-display-{{ $key }}">
                                        @if($profile->{$key})
                                            <a href="{{ $profile->{$key} }}" class="text-sm text-blue-600 break-all block"
                                                target="_blank">
                                                {{ $profile->{$key} }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-500">Belum diisi</span>
                                        @endif
                                    </div>

                                    <form id="social-form-{{ $key }}" method="POST"
                                        action="{{ route('profile.social.update') }}" class="hidden mt-1">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $key }}">
                                        <input type="url" name="link" value="{{ $profile->{$key} }}"
                                            class="w-full border px-2 py-1 text-sm rounded"
                                            placeholder="https://...">
                                        <div class="flex gap-2 mt-1">
                                            <button type="submit"
                                                class="bg-blue-600 text-white px-3 py-1 rounded text-sm">Simpan</button>
                                            <button type="button" onclick="cancelSocialEdit('{{ $key }}')"
                                                class="bg-red-500 text-white px-3 py-1 rounded text-sm">Batal</button>
                                        </div>
                                    </form>
                                </div>

                                <button onclick="editSocial('{{ $key }}')" class="text-blue-600 mt-1">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Kanan --}}
            <div class="md:col-span-2 space-y-4">
                <div class="flex justify-between items-center">
                    <h4 class="font-semibold">Address</h4>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded shadow"
                        onclick="document.getElementById('addAddressForm').classList.remove('hidden')">
                        + Add Address
                    </button>
                </div>

                {{-- Form Add --}}
                <div id="addAddressForm" class="bg-white shadow rounded p-4 hidden">
                    <form action="{{ route('profile.address.store') }}" method="POST" class="grid md:grid-cols-2 gap-4">
                        @csrf
                        <input type="text" name="place_name" class="border px-2 py-1 rounded"
                            placeholder="Nama Tempat" required>
                        <input type="text" name="address" class="border px-2 py-1 rounded" placeholder="Alamat"
                            required>
                        <div class="md:col-span-2">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded text-sm">
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                            <button type="button" onclick="document.getElementById('addAddressForm').classList.add('hidden')"
                                class="bg-gray-500 text-white px-4 py-1 rounded text-sm ml-2">
                                <i class="fas fa-times mr-1"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>

                {{-- List Address --}}
                @forelse ($addresses as $address)
                    <div class="bg-white shadow p-4 relative group rounded">
                        <div id="address-display-{{ $address->id }}">
                            <div class="font-semibold text-blue-700">{{ $address->place_name }}</div>
                            <div class="text-sm text-gray-600">{{ $address->address }}</div>
                        </div>

                        <form id="address-form-{{ $address->id }}" class="hidden mt-2" method="POST"
                            action="{{ route('profile.address.update', $address->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="grid md:grid-cols-2 gap-2">
                                <input type="text" name="place_name" class="px-2 py-1 border rounded text-sm"
                                    value="{{ $address->place_name }}" placeholder="Nama Tempat">
                                <input type="text" name="address" class="px-2 py-1 border rounded text-sm"
                                    value="{{ $address->address }}" placeholder="Alamat">
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <button type="button" onclick="cancelAddressEdit({{ $address->id }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded text-sm">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition">
                            <button onclick="editAddress({{ $address->id }})" class="text-blue-600 text-sm">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form id="delete-form-{{ $address->id }}" method="POST"
                                action="{{ route('profile.address.destroy', $address->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $address->id }})"
                                    class="text-red-600 text-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow p-4 rounded text-center text-gray-500">
                        <i class="fas fa-map-marker-alt text-3xl mb-2"></i>
                        <p>Belum ada alamat yang ditambahkan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        function editField(field) {
            document.getElementById(`${field}-display`).classList.add('hidden');
            document.getElementById(`${field}-form`).classList.remove('hidden');
        }

        function cancelEdit(field) {
            document.getElementById(`${field}-display`).classList.remove('hidden');
            document.getElementById(`${field}-form`).classList.add('hidden');
        }

        function editSocial(type) {
            document.getElementById(`social-display-${type}`).classList.add('hidden');
            document.getElementById(`social-form-${type}`).classList.remove('hidden');
        }

        function cancelSocialEdit(type) {
            document.getElementById(`social-display-${type}`).classList.remove('hidden');
            document.getElementById(`social-form-${type}`).classList.add('hidden');
        }

        function editAddress(id) {
            document.getElementById(`address-display-${id}`).classList.add('hidden');
            document.getElementById(`address-form-${id}`).classList.remove('hidden');
        }

        function cancelAddressEdit(id) {
            document.getElementById(`address-display-${id}`).classList.remove('hidden');
            document.getElementById(`address-form-${id}`).classList.add('hidden');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>

    {{-- SweetAlert Success & Error --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validasi Error!',
                text: '{{ $errors->first() }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif
@endsection
