<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfilController extends Controller
{
    private function currentPegawaiId(): ?int
    {
        $id = session('user_id');
        if ($id) return (int)$id;

        // fallback agar testing tetap jalan
        $first = User::where('role', 'pegawai')->first();
        return $first?->id;
    }

    public function show()
    {
        $pegawaiId = $this->currentPegawaiId();
        if (!$pegawaiId) {
            return back()->withErrors(['login' => 'Pegawai belum teridentifikasi. Pastikan session user_id diset saat login.']);
        }

        // Ambil user + profil pegawai (relasi: pegawaiProfile)
        $user = User::with('pegawaiProfile')->findOrFail($pegawaiId);
        $p = $user->pegawaiProfile; // bisa null kalau belum diisi

        return view('pegawai.profil', compact('user', 'p'));
    }
}
