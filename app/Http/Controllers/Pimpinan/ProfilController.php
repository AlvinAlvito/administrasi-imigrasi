<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfilController extends Controller
{
    private function currentPimpinanId(): ?int
    {
        $id = session('user_id');
        if ($id) return (int)$id;

        // fallback agar testing tetap jalan
        $first = User::where('role', 'pimpinan')->first();
        return $first?->id;
    }

    public function show()
    {
        $pimpinanId = $this->currentPimpinanId();
        if (!$pimpinanId) {
            return back()->withErrors(['login' => 'Pimpinan belum teridentifikasi. Pastikan session user_id diset saat login.']);
        }

        // Ambil user + profil pimpinan (relasi: pimpinanProfile)
        $user = User::with('pimpinanProfile')->findOrFail($pimpinanId);
        $p = $user->pimpinanProfile; // bisa null jika belum diisi

        return view('pimpinan.profil', compact('user', 'p'));
    }
}
