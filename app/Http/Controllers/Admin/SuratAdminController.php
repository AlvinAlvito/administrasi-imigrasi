<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPerjalananDina;

class SuratAdminController extends Controller
{
    public function index()
    {
        $surat = SuratPerjalananDina::with(['pegawai','pimpinan','rincianBiaya'])
                 ->latest('created_at')
                 ->get();

        return view('admin.surat', compact('surat'));
    }
}
