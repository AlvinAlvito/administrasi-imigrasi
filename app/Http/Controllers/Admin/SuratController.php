<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPerjalananDina;

class SuratController extends Controller
{
    public function pdf($id)
    {
        // Ambil surat lengkap (pegawai, pimpinan, rincian, profil ttd)
        $spd = SuratPerjalananDina::with([
            'pegawai.pegawaiProfile',
            'pimpinan.pimpinanProfile',
            'rincianBiaya'
        ])->findOrFail($id);

        // Amankan nama file
        $safeNoSurat = preg_replace('/[\/\\\\]+/','-', $spd->no_surat);
        $safeNoSurat = preg_replace('/[^A-Za-z0-9\-\._]/','_', $safeNoSurat);
        $filename = 'SPD-'.$safeNoSurat.'.pdf';

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
                        'isRemoteEnabled'      => true,
                        'isHtml5ParserEnabled' => true,
                    ])
                    ->loadView('pegawai.surat_pdf', compact('spd'))
                    ->setPaper('A4', 'portrait');

            return $pdf->download($filename);
            // atau: return $pdf->stream($filename);
        }

        // Fallback (debug)
        return view('pegawai.surat_pdf', compact('spd'));
    }
}
