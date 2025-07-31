@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white border-b sticky top-0 z-10 shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <a href="{{ route('career.index') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span class="font-medium">Kembali</span>
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        {{-- Job Header --}}
        <div class="bg-white rounded-xl shadow-sm border p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $career->position_title }}</h1>
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                            {{ $career->job_type }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $career->lokasi }}
                        </span>
                    </div>
                    <p class="text-gray-600 text-lg leading-relaxed">{{ $career->ringkasan }}</p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Job Details --}}
                @if ($career->deskripsi)
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Deskripsi Pekerjaan
                    </h2>
                    <div class="prose prose-gray max-w-none">
                        <ul class="space-y-3">
                            @foreach ($career->deskripsi as $desc)
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $desc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Requirements --}}
                @if ($career->klasifikasi)
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Kualifikasi & Persyaratan
                    </h2>
                    <div class="prose prose-gray max-w-none">
                        <ul class="space-y-3">
                            @foreach ($career->klasifikasi as $klas)
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $klas }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Job Info --}}
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pekerjaan</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pengalaman</p>
                                <p class="text-gray-900 font-medium">{{ $career->pengalaman }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1M8 7h8m-8 0v10a2 2 0 002 2h4a2 2 0 002-2V7"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Hari Kerja</p>
                                <p class="text-gray-900 font-medium">{{ $career->hari_kerja }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Jam Kerja</p>
                                <p class="text-gray-900 font-medium">{{ $career->jam_kerja }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
