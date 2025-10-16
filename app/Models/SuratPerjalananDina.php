<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPerjalananDina extends Model
{
    use HasFactory;

    protected $table = 'surat_perjalanan_dinas';

    protected $fillable = [
        'pegawai_id',
        'pimpinan_id',
        'no_surat',
        'tanggal_pengajuan',
        'tanggal_berangkat',
        'tanggal_kembali',
        'tujuan',
        'maksud_perjalanan',
        'alat_transportasi',
        'status',
        'catatan_pimpinan',
        'file_surat_pdf',
    ];

    // Relasi
    public function pegawai() {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    public function pimpinan() {
        return $this->belongsTo(User::class, 'pimpinan_id');
    }

    public function rincianBiaya() {
        return $this->hasOne(RincianBiaya::class, 'surat_id');
    }

    public function logs() {
        return $this->hasMany(LogAksi::class, 'surat_id');
    }
}
