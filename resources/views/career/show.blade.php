@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        {{-- Header --}}
        <div class="bg-white border-b sticky top-0 z-10 shadow-sm">
            <div class="container mx-auto px-4 py-4">
                <a href="{{ route('career.index') }}"
                    class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="font-medium">Kembali</span>
                </a>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="bg-white border-b">
            <div class="container mx-auto px-4">
                <nav class="flex space-x-8">
                    <button onclick="showTab('detail')" id="tab-detail"
                        class="tab-button active py-4 px-2 border-b-2 border-blue-500 text-blue-600 font-medium text-sm focus:outline-none">
                        Detail Pekerjaan
                    </button>
                    <button onclick="showTab('pelamar')" id="tab-pelamar"
                        class="tab-button py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm focus:outline-none">
                        Pelamar
                        @if (isset($applicants) && $applicants->count() > 0)
                            <span
                                class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-full">
                                {{ $applicants->count() }}
                            </span>
                        @endif
                    </button>
                </nav>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8 max-w-6xl">
            {{-- Tab Content: Detail Pekerjaan --}}
            <div id="content-detail" class="tab-content">
                <div class="bg-white rounded-xl shadow-sm border p-8 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $career->position_title }}</h1>
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ $career->job_type }}
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                                    {{ $career->lokasi }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-lg leading-relaxed">{{ strip_tags($career->ringkasan) }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        @if ($career->deskripsi)
                            <div class="bg-white rounded-xl shadow-sm border p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Pekerjaan</h2>
                                <ul class="space-y-3">
                                    @foreach ($career->deskripsi as $desc)
                                        <li class="flex items-start">
                                            <span class="text-gray-700">{{ strip_tags($desc) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($career->klasifikasi)
                            <div class="bg-white rounded-xl shadow-sm border p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Kualifikasi & Persyaratan</h2>
                                <ul class="space-y-3">
                                    @foreach ($career->klasifikasi as $klas)
                                        <li class="flex items-start">
                                            <span class="text-gray-700">{{ $klas }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white rounded-xl shadow-sm border p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pekerjaan</h3>
                            <p><strong>Pengalaman:</strong> {{ $career->pengalaman }}</p>
                            <p><strong>Hari Kerja:</strong> {{ $career->hari_kerja }}</p>
                            <p><strong>Jam Kerja:</strong> {{ $career->jam_kerja }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Content: Pelamar --}}
            <div id="content-pelamar" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">Daftar Pelamar</h2>
                            @if (isset($applicants) && $applicants->count() > 0)
                                <span class="text-sm text-gray-500">Total: {{ $applicants->count() }} pelamar</span>
                            @endif
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        @if (isset($applicants) && $applicants->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.
                                            Telepon</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                            Melamar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($applicants as $applicant)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">{{ $applicant->nama }}</td>
                                            <td class="px-6 py-4">{{ $applicant->email }}</td>
                                            <td class="px-6 py-4">{{ $applicant->no_telepon ?? '-' }}</td>
                                            <td class="px-6 py-4">
                                                {{ $applicant->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($applicant->file && Storage::disk('public')->exists($applicant->file))
                                                    <a href="{{ route('applications.download', $applicant->id) }}"
                                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors">
                                                        Download Dokumen
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-12 text-gray-500">
                                Belum ada pelamar.
                            </div>
                        @endif
                    </div>

                    @if (isset($applicants) && $applicants->hasPages())
                        <div class="px-6 py-3 border-t border-gray-200">
                            {{ $applicants->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active', 'border-blue-500',
                'text-blue-600'));
            document.getElementById('content-' + tabName).classList.remove('hidden');
            document.getElementById('tab-' + tabName).classList.add('active', 'border-blue-500', 'text-blue-600');
        }
    </script>

    <style>
        .tab-button.active {
            border-bottom-color: #3B82F6;
            color: #2563EB;
        }
    </style>
@endsection
