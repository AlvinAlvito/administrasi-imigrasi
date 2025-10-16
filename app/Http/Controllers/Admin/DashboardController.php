<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SuratPerjalananDina;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai  = User::where('role', 'pegawai')->count();
        $totalPimpinan = User::where('role', 'pimpinan')->count();
        $totalSurat    = SuratPerjalananDina::count();
        $menunggu      = SuratPerjalananDina::where('status', 'menunggu')->count();
        $diterima      = SuratPerjalananDina::where('status', 'diterima')->count();
        $ditolak       = SuratPerjalananDina::where('status', 'ditolak')->count();

        return view('admin.dashboard', compact(
            'totalPegawai','totalPimpinan','totalSurat','menunggu','diterima','ditolak'
        ));
    }
}
