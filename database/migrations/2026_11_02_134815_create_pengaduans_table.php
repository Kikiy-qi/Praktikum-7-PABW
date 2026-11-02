<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengemudi');
            $table->string('kontak');
            $table->string('plat_nomor');
            $table->string('kategori');
            $table->dateTime('jadwal_baru')->nullable();
            $table->text('deskripsi');
            $table->string('file_bukti');
            $table->string('status')->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
