@extends('layouts.app')

@section('title', 'Cek Pengguna - ParkTrack')

@section('content')
<div class="flex justify-center mb-12">
    <div class="w-full max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-bold text-white text-2xl flex items-center">
                <i class="bi bi-person-badge text-brand-500 mr-3"></i>Cek Pengguna
            </h2>
            <a href="{{ route('officer.index') }}" class="border border-slate-600 text-slate-400 hover:text-white hover:border-slate-500 rounded-lg px-4 py-2 text-sm transition-colors">
                Kembali
            </a>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-2xl p-8 shadow-lg mb-8">
            <form action="{{ route('officer.check_user') }}" method="GET" class="flex gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="bi bi-search text-slate-400"></i>
                    </div>
                    <input type="text" name="q" value="{{ $search }}" class="w-full bg-slate-900 border border-slate-700 text-slate-50 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block pl-11 p-4" placeholder="Cari berdasarkan nama, email, nomor HP, atau plat nomor kendaraan..." autofocus>
                </div>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold py-4 px-8 rounded-xl transition-colors shadow">Cari Data</button>
            </form>
        </div>

        @if(empty($users) || count($users) == 0)
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-12 text-center">
                <i class="bi bi-search text-6xl text-slate-600 mb-4 block"></i>
                <h3 class="text-xl font-bold text-white mb-2">Tidak Ditemukan</h3>
                <p class="text-slate-400">
                    {{ empty($search) ? 'Silakan masukkan kata kunci pencarian di atas.' : 'Tidak ada pengguna yang cocok dengan pencarian "' . $search . '"' }}
                </p>
            </div>
        @else
            <div class="space-y-8">
                @foreach($users as $u)
                    <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden shadow-lg">
                        <div class="p-6 border-b border-slate-700 flex flex-col md:flex-row justify-between md:items-center gap-4 bg-slate-900/40">
                            <div class="flex items-center gap-4">
                                <div class="bg-brand-500/20 text-brand-500 w-14 h-14 rounded-full flex items-center justify-center text-2xl font-bold">
                                    {{ strtoupper(substr($u['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-white text-xl mb-1 flex items-center gap-2">
                                        {{ $u['name'] }}
                                        @if($u['has_active'])
                                            <span class="text-[10px] bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded-full uppercase tracking-wider font-bold">Sedang Parkir</span>
                                        @endif
                                    </h3>
                                    <div class="flex items-center gap-4 text-sm text-slate-400">
                                        <span><i class="bi bi-envelope mr-1"></i> {{ $u['email'] }}</span>
                                        <span><i class="bi bi-telephone mr-1"></i> {{ $u['phone'] }}</span>
                                        <span class="text-brand-400 font-bold"><i class="bi bi-wallet2 mr-1"></i> Rp {{ number_format($u['wallet_balance'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-slate-500 block mb-1">Total Riwayat Parkir</span>
                                <span class="text-2xl font-black text-brand-400">{{ count($u['history']) }}</span>
                            </div>
                        </div>
                        <div class="p-6 bg-slate-800">
                            <h4 class="font-bold text-slate-300 mb-4 flex items-center">
                                <i class="bi bi-clock-history mr-2 text-brand-500"></i> Riwayat Parkir
                            </h4>
                            @if(count($u['history']) == 0)
                                <p class="text-slate-500 italic">Belum ada riwayat parkir tercatat.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-slate-300">
                                        <thead class="text-xs text-slate-400 uppercase bg-slate-900/50">
                                            <tr>
                                                <th class="px-4 py-3 rounded-tl-lg">Tanggal</th>
                                                <th class="px-4 py-3">Kendaraan</th>
                                                <th class="px-4 py-3">Plat Nomor</th>
                                                <th class="px-4 py-3">Slot</th>
                                                <th class="px-4 py-3">Biaya</th>
                                                <th class="px-4 py-3 rounded-tr-lg">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($u['history'] as $h)
                                                <tr class="border-b border-slate-700/50 hover:bg-slate-700/20 transition-colors">
                                                    <td class="px-4 py-3">
                                                        <div class="font-semibold text-white">{{ $h->created_at ? $h->created_at->format('d M Y') : '-' }}</div>
                                                        <div class="text-xs text-slate-500">{{ $h->created_at ? $h->created_at->format('H:i') : '-' }}</div>
                                                    </td>
                                                    <td class="px-4 py-3 capitalize">{{ $h->jenis_kendaraan }}</td>
                                                    <td class="px-4 py-3 font-bold text-brand-400 font-mono">{{ $h->plat_nomor }}</td>
                                                    <td class="px-4 py-3">Slot {{ $h->parkingSlot ? $h->parkingSlot->kode_slot : '-' }}</td>
                                                    <td class="px-4 py-3">Rp {{ number_format($h->total_biaya, 0, ',', '.') }}</td>
                                                    <td class="px-4 py-3">
                                                        <span class="px-2.5 py-1 rounded-full text-xs {{ $h->status == 'Aktif' ? 'text-blue-400 bg-blue-500/10' : 'text-green-400 bg-green-500/10' }}">
                                                            {{ $h->status }}
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
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
