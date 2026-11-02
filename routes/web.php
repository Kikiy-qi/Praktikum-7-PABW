<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\PengaduanController;
use App\Models\ParkingSlot;
use App\Models\Reservation;

Route::get('/', function () {
    $available_slots = ParkingSlot::where('status', 'Tersedia')->count();
    return view('pages.dashboard', compact('available_slots'));
})->name('dashboard');

Route::get('/info', function () {
    return view('pages.info');
})->name('info');

Route::get('/denda', function () {
    return view('pages.denda');
})->name('denda');

Route::get('/reservasi', [ReservationController::class, 'index'])->name('reservasi.form');
Route::post('/reservasi/proses', [ReservationController::class, 'proses'])->name('reservasi.proses');
Route::get('/pembayaran', function () {
    return view('pages.pembayaran');
})->name('pembayaran');
Route::get('/status', function () {
    $reservations = Reservation::with('parkingSlot')->latest()->get();
    return view('pages.status', compact('reservations'));
})->name('status');

Route::resource('pengaduan', PengaduanController::class)->only(['create', 'store']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/data-parkir', function () {
        if (auth()->user()->role === 'officer') {
            return redirect()->route('officer.list_parking');
        }
        return redirect()->route('dashboard')->with('error', 'Halaman data parkir hanya dapat diakses oleh petugas.');
    })->name('data-parkir');

    Route::resource('pengaduan', PengaduanController::class)->except(['create', 'store']);

    Route::prefix('officer')->group(function () {
        Route::get('/', [OfficerController::class, 'index'])->name('officer.index');
        Route::get('/list_parking', [OfficerController::class, 'listParking'])->name('officer.list_parking');
        Route::put('/list_parking/{id}/reschedule', [OfficerController::class, 'reschedule'])->name('officer.parking.reschedule');
        Route::get('/check_user', [OfficerController::class, 'checkUser'])->name('officer.check_user');
        Route::match(['get', 'post'], '/penalty', [OfficerController::class, 'penalty'])->name('officer.penalty');
    });
});

