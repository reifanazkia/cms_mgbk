@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        {{-- Header --}}
        <div class="bg-white border-b sticky top-0 z-10">
            <div class="container mx-auto px-4 py-3">
                <a href="{{ route('ourblogs.index') }}"
                    class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="font-medium">Kembali ke Daftar Berita</span>
                </a>
            </div>
        </div>

        <div class="container mx-auto px-4 py-6 max-w-5xl">
            <div class="grid lg:grid-cols-2 gap-8">
                {{-- Featured Image --}}
                <div class="max-w-sm mx-auto lg:max-w-none">
                    <div class="relative group">
                        <div class="overflow-hidden rounded-xl shadow-lg">
                            @if ($blog->image)
                                <div
                                    class="relative w-full h-64 md:h-80 lg:h-[350px] bg-gradient-to-br from-gray-100 to-gray-200">
                                    <img src="{{ asset('storage/' . $blog->image) }}"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        alt="{{ $blog->title }}">
                                </div>
                            @else
                                <div
                                    class="w-full h-64 md:h-80 lg:h-[350px] bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-xl">
                                    <div class="text-center text-gray-500">
                                        <div
                                            class="w-12 h-12 mx-auto mb-3 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-sm">Tidak ada gambar</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Blog Details --}}
                <div class="space-y-6">
                    {{-- Title and Meta --}}
                    <div>
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                            <span>{{ \Carbon\Carbon::parse($blog->pub_date)->format('d M Y') }}</span>
                            <span>•</span>
                            <span>{{ $blog->category->name ?? 'Tanpa Kategori' }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $blog->title }}</h1>
                    </div>

                    {{-- Excerpt --}}
                    @if ($blog->excerpt)
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-gray-700 font-medium">{{ $blog->excerpt }}</p>
                        </div>
                    @endif

                    {{-- Content --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Konten Berita</h3>
                        <div class="prose prose-gray max-w-none">
                            <div class="text-gray-700 leading-relaxed">{!! $blog->description !!}</div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
