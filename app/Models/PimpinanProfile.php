<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PimpinanProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'jabatan',
        'tanda_tangan',
        'jenis_kelamin',
        'eselon',
        'pangkat_gol',
        'tmt',
        'pendidikan',
        'diklat_teknis',
    ];

    protected $casts = [
        'tmt' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
