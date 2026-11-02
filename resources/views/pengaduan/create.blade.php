@extends('layouts.app')

@section('title', 'Form Pengaduan & Reschedule - ParkTrack')

@section('content')
<div class="max-w-xl mx-auto bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-lg mb-12">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-white flex items-center">
            <i class="bi bi-chat-left-text text-brand-500 mr-2.5"></i>Form Pengaduan & Reschedule
        </h3>
        @if(auth()->check() || session('user'))
            <span class="text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 px-3 py-1 rounded-full font-semibold">
                <i class="bi bi-check-circle mr-1"></i>Akun Terverifikasi
            </span>
        @else
            <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full">
                Mode Tamu
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-xl mb-5 flex items-center">
            <i class="bi bi-check-circle-fill mr-2 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @php
        $defaultNama = old('nama_pengemudi', auth()->user()?->name ?? session('name', ''));
        $defaultKontak = old('kontak', auth()->user()?->phone ?? auth()->user()?->email ?? session('email', ''));
    @endphp

    <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Nama Pengemudi</label>
            <input type="text" name="nama_pengemudi" value="{{ $defaultNama }}" placeholder="Nama lengkap pengemudi" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
            @error('nama_pengemudi') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Nomor Telepon / Email Kontak</label>
            <input type="text" name="kontak" value="{{ $defaultKontak }}" placeholder="Contoh: 08123456789 atau nama@gmail.com" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
            <span class="text-[11px] text-slate-400 block mt-1">Petugas akan menghubungi Anda melalui kontak ini terkait status penanganan/reschedule.</span>
            @error('kontak') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Plat Nomor Kendaraan</label>
            <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}" placeholder="Contoh: B 1234 ABCDE" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm uppercase focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
            @error('plat_nomor') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Kategori Aduan</label>
            <select name="kategori" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                <option value="Reschedule Jadwal" {{ old('kategori') == 'Reschedule Jadwal' ? 'selected' : '' }}>Reschedule Jadwal Reservasi</option>
                <option value="Masalah Slot" {{ old('kategori') == 'Masalah Slot' ? 'selected' : '' }}>Masalah Slot Parkir</option>
                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            @error('kategori') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Jadwal Reservasi Baru (Wajib diisi jika minta Reschedule)</label>
            <input type="datetime-local" name="jadwal_baru" value="{{ old('jadwal_baru') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" style="color-scheme: dark;">
            @error('jadwal_baru') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Deskripsi Aduan / Alasan Reschedule</label>
            <textarea name="deskripsi" rows="3" placeholder="Jelaskan kendala parkir atau alasan permintaan pindah jadwal..." class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Upload Bukti Tiket / Foto Kendala (JPG, PNG, PDF maks 2MB)</label>
            <input type="file" name="file_bukti" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-500 file:text-white hover:file:bg-brand-600 cursor-pointer" required>
            @error('file_bukti') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 rounded-xl transition-colors shadow-lg shadow-brand-500/20 active:scale-95 cursor-pointer">
            Kirim Pengaduan
        </button>
    </form>
</div>
@endsection
