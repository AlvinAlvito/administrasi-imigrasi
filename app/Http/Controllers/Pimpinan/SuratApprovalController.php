<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratPerjalananDina;
use App\Models\LogAksi;
use App\Models\User;

class SuratApprovalController extends Controller
{
    private function currentPimpinanId(): ?int
    {
        $id = session('user_id');
        if ($id) return (int)$id;

        $first = User::where('role','pimpinan')->first();
        return $first?->id;
    }

    public function index()
    {
        $pimpinanId = $this->currentPimpinanId();
        if (!$pimpinanId) {
            return back()->withErrors(['login' => 'Pimpinan belum teridentifikasi.']);
        }

        $surat = SuratPerjalananDina::with(['pegawai','rincianBiaya'])
                 ->where('pimpinan_id', $pimpinanId)
                 ->latest('created_at')
                 ->get();

        return view('pimpinan.surat', compact('surat'));
    }

    public function approve($id)
    {
        $pimpinanId = $this->currentPimpinanId();
        $spd = SuratPerjalananDina::where('pimpinan_id',$pimpinanId)->findOrFail($id);

        if ($spd->status !== 'menunggu') {
            return back()->withErrors(['approve' => 'Surat sudah diproses.']);
        }

        $spd->status = 'diterima';
        // catatan_pimpinan optional—biarkan apa adanya
        $spd->save();

        LogAksi::create([
            'surat_id' => $spd->id,
            'user_id'  => $pimpinanId,
            'aksi'     => 'diterima',
            'keterangan' => 'Disetujui pimpinan',
        ]);

        return back()->with('success','Surat berhasil diterima.');
    }

    public function reject(Request $request, $id)
    {
        $pimpinanId = $this->currentPimpinanId();
        $spd = SuratPerjalananDina::where('pimpinan_id',$pimpinanId)->findOrFail($id);

        if ($spd->status !== 'menunggu') {
            return back()->withErrors(['reject' => 'Surat sudah diproses.']);
        }

        $request->validate([
            'catatan_pimpinan' => ['nullable','string','max:500'],
        ]);

        $spd->status = 'ditolak';
        $spd->catatan_pimpinan = $request->catatan_pimpinan;
        $spd->save();

        LogAksi::create([
            'surat_id' => $spd->id,
            'user_id'  => $pimpinanId,
            'aksi'     => 'ditolak',
            'keterangan' => 'Ditolak pimpinan'.($request->catatan_pimpinan ? (': '.$request->catatan_pimpinan) : ''),
        ]);

        return back()->with('success','Surat berhasil ditolak.');
    }
}
