<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianBiaya extends Model
{
    use HasFactory;

    protected $table = 'rincian_biaya';

    protected $fillable = [
        'surat_id',
        'uang_harian',
        'transportasi',
        'jumlah_total',
        'terbilang',
    ];

    public function surat() {
        return $this->belongsTo(SuratPerjalananDina::class, 'surat_id');
    }
}
