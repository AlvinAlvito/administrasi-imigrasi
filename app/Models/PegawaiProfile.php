<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_hp',
        'alamat',
        'unit_kerja',
        'jenis_kelamin',   // new
        'eselon',          // new
        'pangkat_gol',     // new
        'tmt',             // new (date)
        'pendidikan',      // new
        'diklat_teknis',   // new
    ];

    protected $casts = [
        'tmt' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
