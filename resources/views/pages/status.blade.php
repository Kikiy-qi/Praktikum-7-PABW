@extends('layouts.app')

@section('title', 'Status Parkir & Tiket - ParkTrack')

@section('content')
<div class="mb-8">
    <h3 class="font-bold text-white text-xl mb-6">Status Parkir & Tiket Aktif</h3>

    @php
        $latest = $reservations->first();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 h-full flex flex-col justify-between hover:border-slate-600 transition-colors">
            <div>
                <p class="text-slate-400 text-sm mb-2">Tagihan Reservasi Terakhir</p>
                <h1 class="font-bold text-brand-500 text-4xl mb-2">
                    Rp {{ number_format($latest?->total_biaya ?? 0, 0, ',', '.') }}
                </h1>
                <span class="text-slate-400 text-xs flex items-center">
                    <i class="bi bi-shield-check mr-1 text-emerald-400"></i> Terverifikasi oleh Sistem ParkTrack
                </span>
            </div>
            <div class="grid grid-cols-2 mt-8 pt-5 border-t border-slate-700">
                <div class="border-r border-slate-700 pr-4">
                    <p class="text-slate-400 text-sm mb-1">Status Kendaraan</p>
                    <h4 class="font-bold text-emerald-400 text-lg">{{ $latest?->status ?? '-' }}</h4>
                </div>
                <div class="pl-4 text-right">
                    <p class="text-slate-400 text-sm mb-1">Lokasi Slot</p>
                    <h4 class="font-bold text-brand-500 text-lg">
                        Slot {{ $latest?->parkingSlot?->kode_slot ?? '-' }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="bg-brand-500 rounded-xl p-6 h-full text-center flex flex-col justify-center items-center text-white relative overflow-hidden" style="box-shadow: inset 0 0 40px rgba(0,0,0,0.1);">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-400 to-brand-600 opacity-50"></div>
            <div class="relative z-10">
                <h1 class="font-bold text-7xl mb-1 leading-none">{{ date('d') }}</h1>
                <h4 class="font-bold uppercase text-xl mb-6 tracking-wide">{{ date('F Y') }}</h4>
                <h3 class="font-light text-2xl mb-4">{{ date('H:i') }} WIB</h3>
                <div class="bg-white/20 px-4 py-2 rounded-full inline-flex items-center text-xs font-bold mb-2 shadow-sm border border-white/30 backdrop-blur-sm">
                    <i class="bi bi-geo-alt-fill mr-1.5"></i> Gedebage, Bandung
                </div>
            </div>
        </div>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 mt-8 hover:border-slate-600 transition-colors">
        <div class="flex justify-between items-center mb-5">
            <h6 class="font-bold text-brand-500 flex items-center">
                <i class="bi bi-ticket-perforated mr-2"></i>Daftar Tiket Reservasi Aktif
            </h6>
            <a href="/data-parkir" class="text-xs text-brand-400 hover:text-white font-semibold">
                Lihat Semua Tiket &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @forelse($reservations as $tiket)
                <div class="p-5 border border-slate-700 rounded-xl flex justify-between items-center bg-white/5 hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="bg-white p-1 rounded shadow-sm shrink-0">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=PARKTRACK-{{ $tiket->id }}-{{ $tiket->plat_nomor }}" alt="QR" class="w-16 h-16">
                        </div>
                        <div>
                            <h6 class="font-bold text-white text-lg mb-0.5">
                                Slot {{ $tiket->parkingSlot ? $tiket->parkingSlot->kode_slot : '-' }}
                                <span class="text-sm text-slate-400 font-normal ml-1">({{ $tiket->parkingSlot ? $tiket->parkingSlot->lantai : '-' }})</span>
                            </h6>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[11px] text-slate-300 uppercase tracking-wider font-bold flex items-center">
                                    <i class="bi bi-person-badge mr-1.5"></i>{{ $tiket->nama_pengemudi }}
                                </span>
                                <span class="text-[10px] text-brand-400 border border-brand-500/30 bg-brand-500/10 px-2 py-0.5 rounded-full uppercase tracking-wider font-bold">
                                    {{ $tiket->jenis_kendaraan }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-400 block mb-1">
                                <i class="bi bi-calendar3 mr-1.5"></i>Dibuat: {{ $tiket->created_at ? $tiket->created_at->format('d M Y, H:i') : '-' }} WIB
                            </span>
                        </div>
                    </div>
                    <div class="text-right flex flex-col justify-between h-full">
                        <h6 class="font-black text-brand-500 text-xl tracking-wider uppercase bg-brand-500/10 px-3 py-1 rounded">
                            {{ $tiket->plat_nomor }}
                        </h6>
                        <span class="text-xs mt-2 px-2.5 py-0.5 rounded-full {{ $tiket->status == 'Aktif' ? 'text-amber-400 bg-amber-500/10' : 'text-emerald-400 bg-emerald-500/10' }}">
                            {{ $tiket->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-8 text-slate-500">
                    Belum ada tiket reservasi yang tersimpan.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection