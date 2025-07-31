@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white border-b sticky top-0 z-10">
        <div class="container mx-auto px-4 py-3">
            <a href="{{ route('agenda.index') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span class="font-medium">Kembali ke daftar Agenda</span>
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6 max-w-5xl">
        <div class="grid lg:grid-cols-2 gap-8">
            {{-- Agenda Image --}}
            <div class="max-w-sm mx-auto lg:max-w-none">
                <div class="relative group">
                    <div class="overflow-hidden rounded-xl shadow-lg">
                        @if ($agenda->image)
                            <div class="relative w-full h-64 md:h-80 lg:h-[350px] bg-gradient-to-br from-gray-100 to-gray-200">
                                <img src="{{ asset('storage/' . $agenda->image) }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                     alt="{{ $agenda->title }}">

                                <div class="absolute top-4 left-4 bg-blue-500 text-white px-3 py-1 text-xs font-bold rounded-full">
                                    {{ ucfirst($agenda->type) }}
                                </div>

                                <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 text-xs font-bold rounded-full">
                                    {{ $agenda->status }}
                                </div>
                            </div>
                        @else
                            <div class="w-full h-64 md:h-80 lg:h-[350px] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-xl">
                                <div class="text-center text-gray-500">
                                    <div class="w-12 h-12 mx-auto mb-3 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm">Tidak ada gambar</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Agenda Details --}}
            <div class="space-y-6">
                {{-- Title --}}
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $agenda->title }}</h1>
                </div>

                {{-- Date & Time --}}
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-lg font-semibold text-blue-900">Jadwal Acara</span>
                    </div>
                    <p class="text-blue-800 font-medium">
                        {{ $agenda->start_datetime }} s/d {{ $agenda->end_datetime }}
                    </p>
                </div>

                {{-- Event Details --}}
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Tempat</p>
                                    <p class="text-gray-700">{{ $agenda->location }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Penyelenggara</p>
                                    <p class="text-gray-700">{{ $agenda->event_organizer }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Links --}}
                <div class="flex gap-3">
                    @if ($agenda->register_link)
                        <a href="{{ $agenda->register_link }}" target="_blank"
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-xl text-center transition-colors">
                            Daftar Sekarang
                        </a>
                    @endif

                    @if ($agenda->youtube_link)
                        <a href="{{ $agenda->youtube_link }}" target="_blank"
                           class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-xl text-center transition-colors">
                            Tonton di YouTube
                        </a>
                    @endif
                </div>

                {{-- Description --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Acara</h3>
                    <div class="prose prose-gray max-w-none">
                        <p class="text-gray-700 leading-relaxed">{!! $agenda->description !!}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Speakers Section --}}
        <div class="mt-12">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Pembicara</h3>
            @if($agenda->speakers->isEmpty())
                <div class="bg-white border border-gray-200 rounded-xl p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500">Belum ada pembicara ditambahkan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($agenda->speakers as $speaker)
                        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-center gap-4">
                                @if($speaker->photo)
                                    <img src="{{ asset('storage/' . $speaker->photo) }}"
                                         class="w-16 h-16 object-cover rounded-full border-2 border-gray-200"
                                         alt="Foto {{ $speaker->name }}">
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center border-2 border-gray-200">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $speaker->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $speaker->title }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
