<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $namaWilayahDesa = ucwords(setting('sebutan_desa', 'Desa')) . ' ' . identitas('nama_desa');
        $namaKecamatan   = ucwords(setting('sebutan_kecamatan', 'Kecamatan')) . ' ' . identitas('nama_kecamatan');
        $namaProvinsi    = identitas('nama_propinsi') ?: identitas('nama_provinsi');
        $judulLayanan    = 'Layanan Pengaduan Warga ' . $namaWilayahDesa;
        $pageTitle       = trim($__env->yieldContent('title', ''));
        $fullTitle       = ($pageTitle ? $pageTitle . ' | ' : '') . $judulLayanan . ' - ' . $namaKecamatan;
        $deskripsiSeo    = "Portal Aspirasi & Layanan Pengaduan Masyarakat {$namaWilayahDesa}, {$namaKecamatan}, {$namaProvinsi}. Sampaikan keluhan, aspirasi, dan pantau progres penanganan secara transparan.";
        $logoDesaUrl     = gambar_desa(identitas('logo'));
        $faviconUrl      = favico_desa();
    @endphp

    <title>{{ $fullTitle }}</title>

    <!-- SEO & Meta -->
    <meta name="description" content="{{ $deskripsiSeo }}">
    <meta name="keywords" content="Pengaduan Desa, Aspirasi Warga, Layanan Pengaduan, {{ identitas('nama_desa') }}, {{ identitas('nama_kecamatan') }}, Transparansi Desa">
    <meta name="author" content="Pemerintah {{ $namaWilayahDesa }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ current_url() }}">

    <!-- Open Graph -->
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $deskripsiSeo }}">
    <meta property="og:url" content="{{ current_url() }}">
    <meta property="og:image" content="{{ $logoDesaUrl }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ $faviconUrl }}" />
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}" />

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#F0FDF4',
                            100: '#DCFCE7',
                            200: '#BBF7D0',
                            500: '#22C55E',
                            600: '#16A34A',
                            700: '#15803D',
                            800: '#166534',
                            900: '#14532D',
                        },
                    },
                },
            },
        };
    </script>
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('admin.layouts.components.token')

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('css')
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between antialiased">
    <!-- Header Navbar -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200/80 shadow-sm backdrop-blur-md bg-white/95">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between gap-4">
            <a href="{{ site_url('layanan-pengaduan') }}" class="flex items-center gap-3 group">
                <span class="h-11 w-11 rounded-xl bg-slate-50 p-1 border border-slate-200 shadow-sm flex items-center justify-center overflow-hidden shrink-0 group-hover:scale-105 transition transform">
                    <img src="{{ gambar_desa($desa->logo ?? null) }}" alt="Logo Desa" class="h-9 w-9 object-contain">
                </span>
                <div class="leading-tight">
                    <div class="font-bold text-base tracking-tight text-slate-900 group-hover:text-brand-600 transition">
                        Layanan Pengaduan Warga
                    </div>
                    <div class="text-xs text-slate-500 font-medium">
                        {{ ucwords(setting('sebutan_desa', 'Desa')) }} {{ identitas('nama_desa') }}
                    </div>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm">
                <a href="{{ site_url() }}" class="text-slate-600 hover:text-brand-600 px-3 py-1.5 rounded-lg font-medium transition">
                    <i class="fa fa-home mr-1"></i> Beranda Web
                </a>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <main class="max-w-5xl mx-auto w-full px-4 sm:px-6 py-8 sm:py-12 flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500 font-medium">
        <div class="max-w-6xl mx-auto px-4">
            &copy; {{ date('Y') }} Pemerintah {{ ucwords(setting('sebutan_desa', 'Desa')) }} {{ identitas('nama_desa') }}. Layanan Pengaduan & Aspirasi Terpadu.
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
