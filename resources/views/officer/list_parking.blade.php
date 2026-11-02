@extends('layouts.app')

@section('title', 'Daftar Parkir & Reservasi - ParkTrack')

@section('content')
<div class="flex justify-center mb-12">
    <div class="w-full max-w-6xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h2 class="font-bold text-white text-2xl flex items-center">
                <i class="bi bi-list-columns text-brand-500 mr-3"></i>Daftar Parkir & Reservasi
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('pengaduan.index') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg px-4 py-2 text-sm transition-colors whitespace-nowrap">
                    <i class="bi bi-chat-left-dots mr-1.5"></i>Lihat Pengaduan
                </a>
                <a href="{{ route('officer.index') }}" class="border border-slate-600 text-slate-400 hover:text-white hover:border-slate-500 rounded-lg px-4 py-2 text-sm transition-colors whitespace-nowrap">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-xl mb-6 flex items-center justify-between">
                <span><i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-lg mb-8">
            <form action="{{ route('officer.list_parking') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-2/5 relative">
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Pencarian</label>
                    <div class="absolute bottom-0 left-0 pl-4 mb-3.5 flex items-center pointer-events-none">
                        <i class="bi bi-search text-slate-400"></i>
                    </div>
                    <input type="text" name="q" value="{{ $search }}" class="w-full bg-slate-900 border border-slate-700 text-slate-50 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block pl-11 p-3" placeholder="Plat nomor, slot, nama pengemudi...">
                </div>
                <div class="w-full md:w-1/5">
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Status</label>
                    <select name="status" class="w-full bg-slate-900 border border-slate-700 text-slate-50 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 appearance-none cursor-pointer">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Aktif" {{ $status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Selesai" {{ $status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="w-full md:w-1/5">
                    <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide">Urutkan</label>
                    <select name="sort" class="w-full bg-slate-900 border border-slate-700 text-slate-50 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3 appearance-none cursor-pointer">
                        <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Paling Baru</option>
                        <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Paling Lama</option>
                        <option value="status" {{ $sort == 'status' ? 'selected' : '' }}>Berdasarkan Status</option>
                    </select>
                </div>
                <div class="w-full md:w-1/5">
                    <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow">Terapkan</button>
                </div>
            </form>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden shadow-lg">
            @if($reservations->isEmpty())
                <div class="p-12 text-center">
                    <i class="bi bi-inbox text-6xl text-slate-600 mb-4 block"></i>
                    <h3 class="text-xl font-bold text-white mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-slate-400">Silakan ubah filter atau pencarian Anda.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-300">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-900/50">
                            <tr>
                                <th class="px-5 py-4 font-semibold">Pengemudi</th>
                                <th class="px-5 py-4 font-semibold">Plat Nomor</th>
                                <th class="px-5 py-4 font-semibold">Slot & Lantai</th>
                                <th class="px-5 py-4 font-semibold">Jenis & Pembayaran</th>
                                <th class="px-5 py-4 font-semibold">Waktu Reservasi</th>
                                <th class="px-5 py-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @foreach($reservations as $r)
                                <tr class="hover:bg-slate-700/20 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-white">{{ $r->nama_pengemudi }}</div>
                                        <div class="text-xs text-slate-400"><i class="bi bi-telephone mr-1"></i>{{ $r->nomor_telepon }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="bg-slate-900 px-3 py-1 rounded text-white font-mono border border-slate-700 uppercase font-bold tracking-wider">{{ $r->plat_nomor }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-white">Slot {{ $r->parkingSlot ? $r->parkingSlot->kode_slot : '-' }}</div>
                                        <div class="text-xs text-slate-400">{{ $r->parkingSlot ? $r->parkingSlot->lantai : '-' }} &bull; Rp {{ number_format($r->total_biaya, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="text-white font-medium">{{ $r->jenis_kendaraan }}</div>
                                        <div class="text-xs text-brand-400"><i class="bi bi-credit-card mr-1"></i>{{ $r->metode_pembayaran }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-400">
                                        @php
                                            $jadwal = $r->waktu_masuk ? \Carbon\Carbon::parse($r->waktu_masuk) : $r->created_at;
                                        @endphp
                                        <div class="text-white font-medium">{{ $jadwal ? $jadwal->format('d M Y') : '-' }}</div>
                                        <div class="text-xs">{{ $jadwal ? $jadwal->format('H:i') . ' WIB' : '-' }}</div>

                                        <details class="mt-2">
                                            <summary class="text-xs text-brand-500 hover:text-white cursor-pointer font-semibold">
                                                <i class="bi bi-calendar-event mr-1"></i>Edit Jadwal (Reschedule)
                                            </summary>
                                            <form action="{{ route('officer.parking.reschedule', $r->id) }}" method="POST" class="mt-2 flex flex-col gap-2 bg-slate-900 p-2.5 rounded-lg border border-slate-700">
                                                @csrf
                                                @method('PUT')
                                                <input type="datetime-local" name="waktu_masuk" value="{{ $jadwal ? $jadwal->format('Y-m-d\TH:i') : '' }}" required class="bg-slate-800 border border-slate-700 text-white text-xs rounded px-2 py-1.5">
                                                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white text-xs font-bold py-1.5 px-3 rounded transition-colors">
                                                    Simpan Jadwal
                                                </button>
                                            </form>
                                        </details>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $r->status == 'Aktif' ? 'text-amber-400 bg-amber-500/10 border-amber-500/20' : 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' }}">
                                            {{ $r->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection