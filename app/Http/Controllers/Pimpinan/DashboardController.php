<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\SuratPerjalananDina;
use App\Models\User;

class DashboardController extends Controller
{
    private function currentPimpinanId(): ?int
    {
        $id = session('user_id');
        if ($id) return (int)$id;

        // Fallback agar testing tidak buntu
        $first = User::where('role','pimpinan')->first();
        return $first?->id;
    }

    public function index()
    {
        $pimpinanId = $this->currentPimpinanId();
        if (!$pimpinanId) {
            return back()->withErrors(['login' => 'Pimpinan belum teridentifikasi. Pastikan session user_id diset saat login.']);
        }

        $total    = SuratPerjalananDina::where('pimpinan_id',$pimpinanId)->count();
        $menunggu = SuratPerjalananDina::where('pimpinan_id',$pimpinanId)->where('status','menunggu')->count();
        $diterima = SuratPerjalananDina::where('pimpinan_id',$pimpinanId)->where('status','diterima')->count();
        $ditolak  = SuratPerjalananDina::where('pimpinan_id',$pimpinanId)->where('status','ditolak')->count();

        $terbaru  = SuratPerjalananDina::with(['pegawai'])
                    ->where('pimpinan_id',$pimpinanId)
                    ->latest('created_at')
                    ->limit(5)->get();

        return view('pimpinan.dashboard', compact('total','menunggu','diterima','ditolak','terbaru'));
    }
}
