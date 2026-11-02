<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ParkTrack - Solusi Parkir Cerdas')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#3B82F6',
                            600: '#2563EB',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-50 min-h-screen flex flex-col font-sans overflow-x-hidden">

<div class="container mx-auto px-4 mt-6">
    <nav class="flex justify-between items-center py-4 hide-on-print relative">
        <a class="flex items-center gap-2 text-decoration-none group" href="{{ session('role') === 'officer' ? route('officer.index') : '/' }}">
            <img src="{{ asset('img/logo/Logo_new_crop.png') }}" alt="ParkTrack Logo" class="h-10 w-auto object-contain transition-transform group-hover:scale-105">
            <img src="{{ asset('img/logo/Nama_new_crop.png') }}" alt="ParkTrack Text" class="h-6 w-auto object-contain mt-1">
        </a>
        
        <div id="mainNav" class="hidden md:flex flex-col md:flex-row absolute md:relative top-full mt-2 md:mt-0 left-0 right-0 bg-slate-800 border border-slate-700 rounded-2xl md:rounded-full p-4 md:p-1.5 items-stretch md:items-center z-50 md:z-auto gap-2 md:gap-0 shadow-xl md:shadow-none">
            @if(session('role') === 'officer')
                <a href="{{ route('officer.index') }}" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('officer') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-speedometer2 mr-2"></i>Dashboard
                </a>
                <a href="{{ route('officer.list_parking') }}" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('officer/list_parking*') || request()->is('data-parkir*') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-list-columns mr-2"></i>Daftar Parkir
                </a>
                <a href="{{ route('officer.check_user') }}" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('officer/check_user*') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-person-badge mr-2"></i>Cek Pengguna
                </a>
                <a href="{{ route('officer.penalty') }}" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('officer/penalty*') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-file-earmark-ruled mr-2"></i>Pengatur Denda
                </a>
                <a href="{{ route('pengaduan.index') }}" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('pengaduan*') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-chat-left-dots mr-2"></i>Pengaduan
                </a>
            @else
                <a href="/" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('/') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-speedometer2 mr-2"></i>Dashboard
                </a>
                <a href="/reservasi" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('reservasi*') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-calendar-check mr-2"></i>Reservasi Baru
                </a>
                <a href="/status" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('status*') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-ticket-detailed mr-2"></i>Status Tiket
                </a>
                <a href="/info" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('info*') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-info-circle mr-2"></i>Info Area
                </a>
                <a href="/denda" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('denda*') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-exclamation-triangle mr-2"></i>Denda
                </a>
                <a href="{{ route('pengaduan.create') }}" class="rounded-full px-5 py-2 text-sm font-medium transition-colors {{ request()->is('pengaduan*') ? 'bg-brand-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    <i class="bi bi-chat-left-text mr-2"></i>Pengaduan
                </a>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button onclick="toggleTheme()" class="w-10 h-10 rounded-full border border-slate-700 bg-slate-800 text-slate-400 hover:text-white hover:border-slate-600 flex items-center justify-center transition-colors" title="Toggle Light/Dark Mode">
                <i id="theme-icon" class="bi bi-sun-fill"></i>
            </button>
            @if(session('user'))
                <div class="hidden sm:block text-right">
                    <div class="font-bold text-white leading-tight">{{ explode(' ', session('name', 'Petugas'))[0] }}</div>
                    @if(session('role') === 'officer')
                        <div class="text-xs text-slate-400 uppercase tracking-widest mt-0.5">Administrator</div>
                    @else
                        <div class="text-xs text-brand-500 font-bold uppercase">Member</div>
                    @endif
                </div>
                <div class="w-10 h-10 rounded-full bg-brand-500 text-white flex items-center justify-center font-bold hover:ring-2 hover:ring-brand-400 hover:ring-offset-2 hover:ring-offset-slate-900 transition-all overflow-hidden" title="Profil">
                    {{ strtoupper(substr(session('name', 'AD'), 0, 2)) }}
                </div>
                <a href="{{ route('logout') }}" onclick="return confirm('Apakah Anda yakin ingin keluar dari ParkTrack?');" class="text-red-500 hover:text-red-400 transition-colors ml-2" title="Keluar">
                    <i class="bi bi-box-arrow-right text-xl"></i>
                </a>
            @else
                <a href="{{ route('login') }}" class="border border-slate-600 hover:bg-slate-800 text-white px-6 py-2 rounded-full text-sm font-medium transition-colors">Masuk</a>
            @endif
            <button type="button" class="md:hidden ml-3 bg-slate-800 text-white rounded-lg p-2 border border-slate-700" onclick="document.getElementById('mainNav').classList.toggle('hidden');">
                <i class="bi bi-list text-xl"></i>
            </button>
        </div>
    </nav>
</div>

<main class="container mx-auto px-4 mt-8 mb-16 flex-grow">
    @yield('content')
</main>

<footer class="bg-slate-900 border-t border-slate-800 text-slate-500 py-6 mt-auto">
    <div class="container mx-auto px-4 text-center text-sm">
        <p>&copy; {{ date('Y') }} ParkTrack. Solusi Parkir Cerdas.</p>
    </div>
</footer>

<script>
    function toggleTheme() {
        document.body.classList.toggle('light-mode');
        const isLight = document.body.classList.contains('light-mode');
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
        updateThemeIcon();
    }
    
    function updateThemeIcon() {
        const isLight = document.body.classList.contains('light-mode');
        const icon = document.getElementById('theme-icon');
        if(icon) {
            icon.className = isLight ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        if(localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
        }
        updateThemeIcon();
    });
</script>
@stack('scripts')
</body>
</html>