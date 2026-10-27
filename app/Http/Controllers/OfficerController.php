<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingSlot;
use App\Models\Reservation;
use App\Models\User;

class OfficerController extends Controller
{
    private function checkOfficer()
    {
        return session('role') === 'officer' || (auth()->check() && auth()->user()->role === 'officer');
    }

    public function index()
    {
        if (!$this->checkOfficer()) {
            return redirect()->route('login')->with('error', 'Halaman petugas hanya dapat diakses setelah login sebagai petugas.');
        }

        $totalSlots = ParkingSlot::count();
        $availableSlots = ParkingSlot::where('status', 'Tersedia')->count();
        $occupiedSlots = ParkingSlot::where('status', 'Terisi')->count();
        $reservedSlots = ParkingSlot::where('status', 'Booking')->count();

        $slotsSummary = [
            'available' => $availableSlots,
            'occupied' => $occupiedSlots,
            'reserved' => $reservedSlots,
        ];

        $occupiedByType = [
            'reguler' => ParkingSlot::where('status', 'Terisi')->where('tipe_kendaraan', 'Mobil')->count(),
            'vip' => ParkingSlot::where('status', 'Terisi')->where('tipe_kendaraan', 'Motor')->count(),
            'vvip' => 0,
        ];

        $reservedByType = [
            'reguler' => ParkingSlot::where('status', 'Booking')->where('tipe_kendaraan', 'Mobil')->count(),
            'vip' => ParkingSlot::where('status', 'Booking')->where('tipe_kendaraan', 'Motor')->count(),
            'vvip' => 0,
        ];

        $totalByType = [
            'reguler' => ParkingSlot::where('tipe_kendaraan', 'Mobil')->count(),
            'vip' => ParkingSlot::where('tipe_kendaraan', 'Motor')->count(),
            'vvip' => 0,
        ];

        $incomeReservations = (int) Reservation::sum('total_biaya');
        $incomePenalties = 75000;

        return view('officer.index', compact(
            'totalSlots',
            'slotsSummary',
            'occupiedByType',
            'reservedByType',
            'totalByType',
            'incomeReservations',
            'incomePenalties'
        ));
    }

    public function listParking(Request $request)
    {
        if (!$this->checkOfficer()) {
            return redirect()->route('login')->with('error', 'Daftar parkir hanya dapat diakses oleh petugas.');
        }

        $query = Reservation::with('parkingSlot')->latest();

        $search = $request->input('q', '');
        if (!empty($search)) {
            $query->where(function ($sub) use ($search) {
                $sub->where('plat_nomor', 'like', "%{$search}%")
                    ->orWhere('nama_pengemudi', 'like', "%{$search}%");
            });
        }

        $status = $request->input('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'status') {
            $query->orderBy('status', 'asc');
        }

        $reservations = $query->get();

        return view('officer.list_parking', compact('reservations', 'search', 'status', 'sort'));
    }

    public function checkUser(Request $request)
    {
        if (!$this->checkOfficer()) {
            return redirect()->route('login')->with('error', 'Halaman cek pengguna hanya dapat diakses oleh petugas.');
        }

        $search = $request->input('q', '');

        $usersQuery = empty($search)
            ? User::where('role', 'user')->get()
            : User::where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->get();

        $merged = collect();

        foreach ($usersQuery as $u) {
            $history = Reservation::where('nomor_telepon', $u->phone)->orWhere('nama_pengemudi', $u->name)->get();
            $merged->push([
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone ?? '-',
                'wallet_balance' => 150000,
                'history' => $history,
                'has_active' => $history->where('status', 'Aktif')->isNotEmpty(),
            ]);
        }

        if (!empty($search)) {
            $resUsers = Reservation::where('nama_pengemudi', 'like', "%{$search}%")
                ->orWhere('plat_nomor', 'like', "%{$search}%")
                ->orWhere('nomor_telepon', 'like', "%{$search}%")
                ->get();

            foreach ($resUsers as $r) {
                if (!$merged->contains('name', $r->nama_pengemudi)) {
                    $history = Reservation::where('nama_pengemudi', $r->nama_pengemudi)->get();
                    $merged->push([
                        'id' => $r->id,
                        'name' => $r->nama_pengemudi,
                        'email' => strtolower(str_replace(' ', '', $r->nama_pengemudi)) . '@gmail.com',
                        'phone' => $r->nomor_telepon,
                        'wallet_balance' => 50000,
                        'history' => $history,
                        'has_active' => $history->where('status', 'Aktif')->isNotEmpty(),
                    ]);
                }
            }
        }

        $users = $merged;

        return view('officer.check_user', compact('users', 'search'));
    }

    public function penalty(Request $request)
    {
        if (!$this->checkOfficer()) {
            return redirect()->route('login')->with('error', 'Halaman denda hanya dapat diakses oleh petugas.');
        }

        $success = null;

        if ($request->isMethod('post')) {
            $success = 'Denda sebesar Rp ' . number_format($request->input('amount', 50000), 0, ',', '.') . ' untuk kendaraan ' . strtoupper($request->input('plate')) . ' berhasil dicatat.';
        }

        return view('officer.penalty', compact('success'));
    }
}
