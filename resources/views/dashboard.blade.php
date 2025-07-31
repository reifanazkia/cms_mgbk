@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="relative w-full max-w-6xl mx-auto">
        {{-- Slider Wrapper --}}
        <div id="slider" class="relative overflow-hidden rounded-xl shadow-lg">
            @if ($sliders->count() > 0)
                {{-- Slide Items dari Database --}}
                @foreach ($sliders as $index => $slider)
                    <div
                        class="slide {{ $index === 0 ? 'opacity-100' : 'opacity-0 absolute' }} inset-0 transition-opacity duration-700 ease-in-out">
                        <div class="relative h-64 md:h-80 lg:h-96">
                            {{-- Background Image --}}
                            @if ($slider->image)
                                <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}"
                                    class="w-full h-full object-cover">
                                {{-- Overlay untuk keterbacaan text --}}
                                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                            @else
                                {{-- Fallback jika tidak ada gambar --}}
                                <div class="w-full h-full bg-gradient-to-r from-purple-500 to-blue-500"></div>
                            @endif

                            {{-- Content Overlay --}}
                            <div
                                class="absolute inset-0 flex flex-col items-center justify-center text-center text-white px-4">
                                @if ($slider->title)
                                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-2 drop-shadow-lg">
                                        {{ $slider->title }}
                                    </h2>
                                @endif

                                {{-- Button jika ada --}}
                                @if ($slider->button_text && $slider->url_link)
                                    <a href="{{ $slider->url_link }}"
                                        class="mt-4 bg-white text-gray-800 px-6 py-2 rounded-full font-semibold hover:bg-gray-100 transition-colors duration-200">
                                        {{ $slider->button_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Fallback jika tidak ada data slider --}}
                <div
                    class="slide bg-purple-400 text-white h-64 flex flex-col items-center justify-center text-center transition-opacity duration-700 ease-in-out opacity-100">
                    <h2 class="text-3xl font-bold mb-2">Selamat Datang</h2>
                    <p class="text-xl font-semibold">Belum ada slider yang tersedia</p>
                </div>
            @endif
        </div>

        {{-- Navigasi Manual --}}
        @if ($sliders->count() > 1)
            <div class="mt-4 flex justify-center space-x-2">
                @foreach ($sliders as $index => $slider)
                    <button onclick="showSlide({{ $index }})"
                        class="w-3 h-3 rounded-full bg-gray-400 hover:bg-gray-600 transition-colors duration-200 slide-indicator {{ $index === 0 ? 'bg-gray-600' : '' }}">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Konten Lain di bawah Slider --}}
    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Agenda Terdekat</h3>
            @if ($agendas->count())
                <ul class="text-gray-600 space-y-2">
                    @foreach ($agendas as $agenda)
                        <li>
                            <strong>{{ \Carbon\Carbon::parse($agenda->start_datetime)->translatedFormat('d F Y') }}</strong>
                            –
                            {{ $agenda->title }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">Belum ada agenda terdekat.</p>
            @endif
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">KTA Online</h3>
            <p class="text-gray-600">Kini anggota dapat mengakses KTA secara digital.</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Loker Terkini</h3>
            @if ($totalLoker > 0)
                <p class="text-gray-600">Terdapat <strong>{{ $totalLoker }}</strong> lowongan yang tersedia.</p>
            @else
                <p class="text-gray-500">Belum ada lowongan tersedia saat ini.</p>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const indicators = document.querySelectorAll('.slide-indicator');
        const totalSlides = {{ $sliders->count() }};

        function showSlide(index) {
            // Update slides
            slides.forEach((slide, i) => {
                slide.classList.toggle('opacity-100', i === index);
                slide.classList.toggle('opacity-0', i !== index);
                slide.classList.toggle('absolute', i !== index);
            });

            // Update indicators
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('bg-gray-600', i === index);
                indicator.classList.toggle('bg-gray-400', i !== index);
            });

            currentSlide = index;
        }

        function nextSlide() {
            if (totalSlides > 1) {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }
        }

        // Auto-slide jika ada lebih dari 1 slide
        @if ($sliders->count() > 1)
            setInterval(nextSlide, 5000); // Change slide every 5 seconds
        @endif

        window.onload = () => {
            showSlide(0); // Start with first slide
        }
    </script>
@endpush
