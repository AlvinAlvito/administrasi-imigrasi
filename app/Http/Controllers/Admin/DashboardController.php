<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SuratPerjalananDina;

class DashboardController extends Controller
{
    public function index()
    {
        // ====== Statistik ringkas ======
        $totalPegawai  = User::where('role', 'pegawai')->count();
        $totalPimpinan = User::where('role', 'pimpinan')->count();

        // Jika jumlah surat cukup besar, pertimbangkan indexing kolom "status"
        $totalSurat = SuratPerjalananDina::count();
        $menunggu   = SuratPerjalananDina::where('status', 'menunggu')->count();
        $diterima   = SuratPerjalananDina::where('status', 'diterima')->count();
        $ditolak    = SuratPerjalananDina::where('status', 'ditolak')->count();

        // ====== Daftar (preview) Pegawai & Pimpinan untuk tabel ======
        // Ambil 10 terbaru, lengkap dengan profilnya (eager load)
        $pegawaiList = User::with('pegawaiProfile')
            ->where('role', 'pegawai')
            ->latest('id')
            ->take(10)
            ->get();

        $pimpinanList = User::with('pimpinanProfile')
            ->where('role', 'pimpinan')
            ->latest('id')
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'totalPegawai'  => $totalPegawai,
            'totalPimpinan' => $totalPimpinan,
            'totalSurat'    => $totalSurat,
            'menunggu'      => $menunggu,
            'diterima'      => $diterima,
            'ditolak'       => $ditolak,
            'pegawaiList'   => $pegawaiList,
            'pimpinanList'  => $pimpinanList,
        ]);
    }
}
