<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduans';

    protected $fillable = [
        'nama_pengemudi',
        'kontak',
        'plat_nomor',
        'kategori',
        'jadwal_baru',
        'deskripsi',
        'file_bukti',
        'status',
    ];
}
