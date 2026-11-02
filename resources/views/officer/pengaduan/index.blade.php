@extends('layouts.app')

@section('title', 'Kelola Pengaduan - ParkTrack')

@section('content')
<div class="max-w-6xl mx-auto mb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-white flex items-center">
            <i class="bi bi-chat-left-dots text-brand-500 mr-3"></i>Daftar Pengaduan & Permintaan Reschedule
        </h2>
        <div class="flex items-center gap-3">
            <a href="{{ route('officer.list_parking') }}" class="border border-slate-600 text-slate-300 hover:text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="bi bi-list-columns mr-1.5"></i>Ke Daftar Parkir
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-xl mb-6 flex items-center">
            <i class="bi bi-check-circle-fill mr-2 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-900/50">
                    <tr>
                        <th class="px-5 py-4">Pengemudi & Kontak</th>
                        <th class="px-5 py-4">Plat Nomor</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4">Permintaan Jadwal Baru</th>
                        <th class="px-5 py-4">Deskripsi</th>
                        <th class="px-5 py-4">File Bukti</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($pengaduans as $item)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-white">{{ $item->nama_pengemudi }}</div>
                                <div class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                    <i class="bi bi-telephone text-brand-400"></i>
                                    <span>{{ $item->kontak }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="bg-slate-900 px-2.5 py-1 rounded text-xs font-mono border border-slate-700 text-white uppercase font-bold tracking-wider">{{ $item->plat_nomor }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs px-2.5 py-1 rounded-full {{ $item->kategori == 'Reschedule Jadwal' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-slate-700 text-slate-300' }}">
                                    {{ $item->kategori }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-white">
                                @if($item->jadwal_baru)
                                    <div class="font-medium text-emerald-400">{{ \Carbon\Carbon::parse($item->jadwal_baru)->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($item->jadwal_baru)->format('H:i') }} WIB</div>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-slate-300 max-w-xs line-clamp-2">{{ $item->deskripsi }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ asset('storage/' . $item->file_bukti) }}" target="_blank" download class="text-brand-400 hover:text-brand-300 hover:underline font-semibold text-xs flex items-center gap-1">
                                    <i class="bi bi-file-earmark-arrow-down text-base"></i>
                                    <span>Lihat/Unduh</span>
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $item->status == 'Menunggu' ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : ($item->status == 'Diproses' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20') }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('pengaduan.edit', $item->id) }}" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold px-3 py-1.5 rounded-lg text-xs transition-colors">Edit</a>
                                    <form action="{{ route('pengaduan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus pengaduan dan file buktinya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition-colors cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                <i class="bi bi-inbox text-5xl text-slate-600 block mb-3"></i>
                                <p>Belum ada data pengaduan masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengaduans->hasPages())
            <div class="p-4 border-t border-slate-700">
                {{ $pengaduans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
