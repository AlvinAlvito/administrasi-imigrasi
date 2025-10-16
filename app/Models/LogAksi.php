<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAksi extends Model
{
    use HasFactory;

    protected $table = 'log_aksi';

    protected $fillable = [
        'surat_id',
        'user_id',
        'aksi',
        'keterangan',
    ];

    public function surat() {
        return $this->belongsTo(SuratPerjalananDina::class, 'surat_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
