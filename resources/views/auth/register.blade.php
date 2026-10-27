@extends('layouts.app')

@section('title', 'Daftar Akun Baru - ParkTrack')

@section('content')
<div class="flex justify-center items-center py-12">
    <div class="w-full max-w-md">
        <div class="bg-slate-800 border border-slate-700 rounded-2xl shadow-xl overflow-hidden hover:border-slate-600 transition-colors">
            <div class="bg-slate-900 border-b border-slate-700 p-6 text-center">
                <h4 class="font-bold text-white text-xl tracking-tight">Daftar Akun Baru</h4>
                <p class="text-slate-400 text-sm mt-1">Daftarkan akun untuk kemudahan reservasi dan akses parkir.</p>
            </div>
            <div class="p-8">
                <form action="{{ route('register.post') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-slate-300 text-sm font-semibold mb-2">Nama Lengkap</label>
                        <input type="text" id="regName" name="name" value="{{ old('name') }}" class="w-full bg-slate-900 border @error('name') border-red-500 @else border-slate-700 @enderror text-slate-50 placeholder:text-slate-500 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3" required placeholder="Nama lengkap sesuai identitas">
                        @error('name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-slate-300 text-sm font-semibold mb-2">Alamat Email / Nomor Telepon</label>
                        <input type="text" id="regContact" name="contact_id" value="{{ old('contact_id') }}" class="w-full bg-slate-900 border @error('contact_id') border-red-500 @else border-slate-700 @enderror text-slate-50 placeholder:text-slate-500 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3" required placeholder="nama@gmail.com / 081234567890">
                        @error('contact_id')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-slate-300 text-sm font-semibold mb-2">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" id="regPassword" name="password" minlength="8" class="w-full bg-slate-900 border @error('password') border-red-500 @else border-slate-700 @enderror text-slate-50 placeholder:text-slate-500 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3 pr-11" required placeholder="Minimal 8 karakter">
                            <button type="button" onclick="togglePassword('regPassword', 'regEyeIcon')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors focus:outline-none p-1">
                                <i class="bi bi-eye text-lg" id="regEyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-slate-300 text-sm font-semibold mb-2">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <input type="password" id="regPasswordConfirmation" name="password_confirmation" minlength="8" class="w-full bg-slate-900 border border-slate-700 text-slate-50 placeholder:text-slate-500 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-3 pr-11" required placeholder="Ulangi kata sandi">
                            <button type="button" onclick="togglePassword('regPasswordConfirmation', 'regEyeConfIcon')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors focus:outline-none p-1">
                                <i class="bi bi-eye text-lg" id="regEyeConfIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 px-4 rounded-xl transition-colors mb-4 shadow">Daftar Sekarang</button>
                    <div class="text-center mt-4">
                        <span class="text-slate-400 text-sm">Sudah memiliki akun?</span>
                        <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-400 text-sm font-bold ml-1 transition-colors">Masuk di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if(input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endsection
