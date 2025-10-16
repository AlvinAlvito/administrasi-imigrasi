<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','role','nip','jabatan'
    ];

    protected $hidden = [
        'password','remember_token',
    ];

    // Profiles
    public function pegawaiProfile() {
        return $this->hasOne(PegawaiProfile::class, 'user_id');
    }

    public function pimpinanProfile() {
        return $this->hasOne(PimpinanProfile::class, 'user_id');
    }

    // Surat sebagai pegawai (pengaju)
    public function suratDiajukan() {
        return $this->hasMany(SuratPerjalananDina::class, 'pegawai_id');
    }

    // Surat sebagai pimpinan (approver)
    public function suratDiverifikasi() {
        return $this->hasMany(SuratPerjalananDina::class, 'pimpinan_id');
    }

    // Log aksi yang dilakukan user
    public function logAksi() {
        return $this->hasMany(LogAksi::class, 'user_id');
    }
}
