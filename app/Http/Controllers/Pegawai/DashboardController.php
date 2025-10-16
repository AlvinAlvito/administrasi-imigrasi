<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\SuratPerjalananDina;
use App\Models\User;

class DashboardController extends Controller
{
    private function currentPegawaiId(): ?int
    {
        $id = session('user_id');
        if ($id) return (int) $id;

        // Fallback agar tetap bisa testing bila lupa set session saat login manual
        $first = User::where('role','pegawai')->first();
        return $first?->id;
    }

    public function index()
    {
        $pegawaiId = $this->currentPegawaiId();
        if (!$pegawaiId) {
            return back()->withErrors(['login' => 'Pegawai belum teridentifikasi. Pastikan session user_id diset saat login.']);
        }

        $total     = SuratPerjalananDina::where('pegawai_id',$pegawaiId)->count();
        $menunggu  = SuratPerjalananDina::where('pegawai_id',$pegawaiId)->where('status','menunggu')->count();
        $diterima  = SuratPerjalananDina::where('pegawai_id',$pegawaiId)->where('status','diterima')->count();
        $ditolak   = SuratPerjalananDina::where('pegawai_id',$pegawaiId)->where('status','ditolak')->count();

        $terbaru   = SuratPerjalananDina::with('pimpinan')
                        ->where('pegawai_id',$pegawaiId)
                        ->latest('created_at')
                        ->limit(5)->get();

        return view('pegawai.dashboard', compact('total','menunggu','diterima','ditolak','terbaru'));
    }
}
