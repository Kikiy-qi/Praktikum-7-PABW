@extends('layouts.app')

@section('title', 'Edit Pengaduan - ParkTrack')

@section('content')
<div class="max-w-xl mx-auto bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-lg mb-12">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-white flex items-center">
            <i class="bi bi-pencil-square text-brand-500 mr-2.5"></i>Edit Pengaduan & Status
        </h3>
        <a href="{{ route('pengaduan.index') }}" class="text-xs text-slate-400 hover:text-white">
            &larr; Kembali
        </a>
    </div>

    <form action="{{ route('pengaduan.update', $pengaduan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Nama Pengemudi</label>
            <input type="text" name="nama_pengemudi" value="{{ old('nama_pengemudi', $pengaduan->nama_pengemudi) }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
            @error('nama_pengemudi') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Nomor Telepon / Email Kontak</label>
            <input type="text" name="kontak" value="{{ old('kontak', $pengaduan->kontak) }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
            @error('kontak') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Plat Nomor</label>
            <input type="text" name="plat_nomor" value="{{ old('plat_nomor', $pengaduan->plat_nomor) }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm uppercase focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
            @error('plat_nomor') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Kategori</label>
            <select name="kategori" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                @foreach(['Reschedule Jadwal', 'Masalah Slot', 'Lainnya'] as $kat)
                    <option value="{{ $kat }}" {{ old('kategori', $pengaduan->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
            @error('kategori') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Jadwal Baru</label>
            <input type="datetime-local" name="jadwal_baru" value="{{ old('jadwal_baru', $pengaduan->jadwal_baru ? \Carbon\Carbon::parse($pengaduan->jadwal_baru)->format('Y-m-d\TH:i') : '') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" style="color-scheme: dark;">
            @error('jadwal_baru') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
            @error('deskripsi') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Status Penanganan</label>
            <select name="status" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                @foreach(['Menunggu', 'Diproses', 'Selesai'] as $st)
                    <option value="{{ $st }}" {{ old('status', $pengaduan->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
            @error('status') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div>
            <label class="block text-sm text-slate-300 mb-1 font-semibold">Ganti File Bukti (Kosongkan jika tidak diubah)</label>
            <div class="mb-2">
                <a href="{{ asset('storage/' . $pengaduan->file_bukti) }}" target="_blank" class="text-brand-400 text-xs hover:underline flex items-center gap-1">
                    <i class="bi bi-box-arrow-up-right"></i> Lihat File Bukti Saat Ini
                </a>
            </div>
            <input type="file" name="file_bukti" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-500 file:text-white hover:file:bg-brand-600 cursor-pointer">
            @error('file_bukti') <small class="text-red-400 block mt-1">{{ $message }}</small> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-colors cursor-pointer">
                Simpan Perubahan
            </button>
            <a href="{{ route('pengaduan.index') }}" class="border border-slate-600 text-slate-300 hover:text-white px-5 py-2.5 rounded-xl text-sm transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
