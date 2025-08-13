<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MGBK')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <!-- Custom CSS untuk CKEditor styling -->
    <style>
        /* Styling for CKEditor content */
        .ck-editor__editable {
            min-height: 200px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .ck.ck-editor {
            width: 100%;
        }

        /* Ensure numbered and bulleted lists are properly styled */
        .ck-content ol {
            list-style-type: decimal;
            margin-left: 1.5em;
            padding-left: 0;
        }

        .ck-content ul {
            list-style-type: disc;
            margin-left: 1.5em;
            padding-left: 0;
        }

        .ck-content ol li,
        .ck-content ul li {
            margin-bottom: 0.5em;
            padding-left: 0.5em;
        }

        /* Additional styling for better list appearance */
        .ck-content ol ol {
            list-style-type: lower-alpha;
            margin-top: 0.5em;
        }

        .ck-content ol ol ol {
            list-style-type: lower-roman;
        }

        .ck-content ul ul {
            list-style-type: circle;
            margin-top: 0.5em;
        }

        .ck-content ul ul ul {
            list-style-type: square;
        }

        /* Enhanced table styling */
        .ck-content table {
            border-collapse: collapse;
            margin: 1em 0;
            width: 100%;
        }

        .ck-content table td,
        .ck-content table th {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .ck-content table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        /* Blockquote styling */
        .ck-content blockquote {
            border-left: 4px solid #ccc;
            margin: 1em 0;
            padding: 0.5em 1em;
            background-color: #f9f9f9;
            font-style: italic;
        }

        /* Enhanced modal styling */
        #addModal .ck.ck-editor,
        #editModal .ck.ck-editor {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }

        /* Loading animation for editor initialization */
        .editor-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 200px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: #f9fafb;
        }

        .editor-loading::after {
            content: "Memuat editor...";
            color: #6b7280;
            font-size: 14px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .ck-editor__editable {
                min-height: 150px;
                font-size: 14px;
            }

            .ck.ck-toolbar {
                flex-wrap: wrap;
            }
        }

        /* Custom scrollbar for modal */
        #addModal .overflow-y-auto::-webkit-scrollbar,
        #editModal .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        #addModal .overflow-y-auto::-webkit-scrollbar-track,
        #editModal .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #addModal .overflow-y-auto::-webkit-scrollbar-thumb,
        #editModal .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        #addModal .overflow-y-auto::-webkit-scrollbar-thumb:hover,
        #editModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth transitions */
        * {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    <script>
        function toggleDropdown(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }

        // Function to highlight active menu
        function highlightActiveMenu() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('nav a');

            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPath) {
                    link.classList.add('bg-purple-100', 'text-purple-700', 'font-medium');
                    link.classList.remove('text-gray-700');
                } else {
                    link.classList.remove('bg-purple-100', 'text-purple-700', 'font-medium');
                    link.classList.add('text-gray-700');
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id$="-dropdown"]');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target) && !event.target.closest('button[onclick*="' + dropdown
                        .id + '"]')) {
                    dropdown.classList.add('hidden');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', highlightActiveMenu);
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar - Fixed -->
        <aside class="w-64 bg-white shadow-lg border-r border-gray-200 flex-shrink-0 fixed h-full overflow-y-auto">
            <!-- Logo Section -->
            <div class="py-6 px-6 border-b border-gray-200">
                <div class="flex flex-col items-center space-y-3">
                    <img src="{{ asset('storage/logo.png') }}" class="h-12 w-12 object-contain" alt="Logo MGBK">
                    <div class="text-center">
                        <h2 class="text-xs font-bold text-purple-600">MGBK</h2>
                        <p class="text-xs text-gray-400">Management System</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="py-4 px-4 space-y-6">
                <!-- DASHBOARD -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Dashboard</h3>
                    <a href="/dashboard"
                        class="group flex items-center px-4 py-3 rounded-lg text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Beranda</span>
                    </a>
                </div>

                <!-- PROFIL & AKUN -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Profil & Akun
                    </h3>
                    <a href="/profile-setting"
                        class="group flex items-center px-4 py-3 rounded-lg text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profile Saya</span>
                    </a>
                </div>

                <!-- KONTEN & INFORMASI -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Konten &
                        Informasi</h3>
                    <div class="space-y-1">
                        <!-- Konten Utama -->
                        <button onclick="toggleDropdown('konten-dropdown')"
                            class="group w-full flex items-center justify-between px-4 py-3 rounded-lg text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <span>Konten Utama</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="konten-dropdown" class="ml-8 space-y-1 hidden">
                            <a href="{{ route('agenda.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Agenda</a>
                            <a href="{{ route('ourblogs.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Berita</a>
                            <a href="{{ route('tentangkami.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Tentang
                                Kami</a>
                            <a href="{{ route('slider.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Slider</a>
                        </div>

                        <!-- Kategori Management -->
                        <button onclick="toggleDropdown('kategori-dropdown')"
                            class="group w-full flex items-center justify-between px-4 py-3 rounded-lg text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <span>Kategori</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="kategori-dropdown" class="ml-8 space-y-1 hidden">
                            <a href="{{ route('category.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Kategori
                                Berita</a>
                            <a href="{{ route('category-anggota.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Kategori
                                Anggota</a>
                            <a href="{{ route('category-kegiatan.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Kategori
                                Kegiatan</a>
                            <a href="{{ route('category-tentangkami.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Kategori
                                Tentangkami</a>
                            <a href="{{ route('category-store.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Kategori
                                Store</a>
                        </div>
                    </div>
                </div>

                <!-- AKTIVITAS & LAYANAN -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Aktivitas &
                        Layanan</h3>
                    <div class="space-y-1">
                        <!-- Kegiatan & Event -->
                        <button onclick="toggleDropdown('kegiatan-dropdown')"
                            class="group w-full flex items-center justify-between px-4 py-3 rounded-lg text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Kegiatan & Event</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="kegiatan-dropdown" class="ml-8 space-y-1 hidden">
                            <a href="{{ route('kegiatan.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Kegiatan</a>
                            <a href="{{ route('agenda-speakers.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Pembicara</a>
                        </div>

                        <!-- Layanan Digital -->
                        <a href="{{ route('products.index') }}"
                            class="group flex items-center px-4 py-3 rounded-lg text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span>Store</span>
                        </a>

                        <a href="{{ route('hows.index') }}"
                            class="group flex items-center px-4 py-3 rounded-lg text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                            <span>KTA (Kartu Tanda Anggota)</span>
                        </a>
                    </div>
                </div>

                <!-- MANAJEMEN ORGANISASI -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Manajemen
                        Organisasi</h3>
                    <div class="space-y-1">
                        <button onclick="toggleDropdown('organisasi-dropdown')"
                            class="group w-full flex items-center justify-between px-4 py-3 rounded-lg text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Keanggotaan</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="organisasi-dropdown" class="ml-8 space-y-1 hidden">
                            <a href="{{ route('anggota.index') }}"
                                class="block px-4 py-2 rounded-lg text-xs text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">Daftar
                                Anggota</a>
                        </div>
                    </div>
                </div>

                <!-- KARIR & PELUANG -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Karir & Peluang
                    </h3>
                    <a href="{{ route('career.index') }}"
                        class="group flex items-center px-4 py-3 rounded-lg text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z" />
                        </svg>
                        <span>Lowongan Kerja</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area - dengan margin left untuk sidebar -->
        <div class="flex-1 flex flex-col min-w-0 ml-64">
            <!-- Header - Fixed/Sticky -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- User Menu -->
                    <div class="flex items-center ml-auto">
                        <!-- User Dropdown -->
                        <div class="relative">
                            <button onclick="toggleDropdown('user-dropdown')"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
                                <div class="w-6 h-6 rounded-full bg-purple-600 flex items-center justify-center">
                                    @if (auth()->user()->profile_picture)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                                            class="w-full h-full rounded-full object-cover" alt="Profile Picture">
                                    @else
                                        <span class="text-white text-xs font-medium">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-xs font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-400">{{ auth()->user()->role }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-dropdown"
                                class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-6 h-6 rounded-full bg-purple-600 flex items-center justify-center">
                                            @if (auth()->user()->profile_picture)
                                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                                                    class="w-full h-full rounded-full object-cover"
                                                    alt="Profile Picture">
                                            @else
                                                <span class="text-white text-xs font-medium">
                                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-400">{{ auth()->user()->role }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-2">
                                    <a href="/profile-setting"
                                        class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profile Saya
                                    </a>
                                </div>

                                <div class="border-t border-gray-200 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6 bg-gray-50 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
