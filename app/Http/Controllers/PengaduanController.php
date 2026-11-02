<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::latest()->paginate(10);
        return view('officer.pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        return view('pengaduan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengemudi' => 'required|string|max:255',
            'kontak'         => 'required|string|max:100',
            'plat_nomor'     => 'required|string|max:20',
            'kategori'       => 'required|in:Reschedule Jadwal,Masalah Slot,Lainnya',
            'jadwal_baru'    => 'nullable|date',
            'deskripsi'      => 'required|string',
            'file_bukti'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['file_bukti'] = $request->file('file_bukti')->store('bukti-pengaduan', 'public');
        $validated['status'] = 'Menunggu';

        Pengaduan::create($validated);

        return back()->with('success', 'Pengaduan berhasil dikirim ke Admin ParkTrack!');
    }

    public function show(Pengaduan $pengaduan)
    {
        return view('officer.pengaduan.show', compact('pengaduan'));
    }

    public function edit(Pengaduan $pengaduan)
    {
        return view('officer.pengaduan.edit', compact('pengaduan'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'nama_pengemudi' => 'required|string|max:255',
            'kontak'         => 'required|string|max:100',
            'plat_nomor'     => 'required|string|max:20',
            'kategori'       => 'required|in:Reschedule Jadwal,Masalah Slot,Lainnya',
            'jadwal_baru'    => 'nullable|date',
            'deskripsi'      => 'required|string',
            'status'         => 'required|in:Menunggu,Diproses,Selesai',
            'file_bukti'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('file_bukti')) {
            if ($pengaduan->file_bukti && Storage::disk('public')->exists($pengaduan->file_bukti)) {
                Storage::disk('public')->delete($pengaduan->file_bukti);
            }
            $validated['file_bukti'] = $request->file('file_bukti')->store('bukti-pengaduan', 'public');
        }

        $pengaduan->update($validated);

        return redirect()->route('pengaduan.index')->with('success', 'Data pengaduan berhasil diperbarui!');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        if ($pengaduan->file_bukti && Storage::disk('public')->exists($pengaduan->file_bukti)) {
            Storage::disk('public')->delete($pengaduan->file_bukti);
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan beserta file bukti berhasil dihapus!');
    }
}