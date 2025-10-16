<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\User;
use App\Models\SuratPerjalananDina;
use App\Models\RincianBiaya;
use App\Models\LogAksi;

class SuratSayaController extends Controller
{
    /**
     * Ambil ID pegawai dari session (manual auth).
     * Pastikan saat login, set: session(['user_id' => $userId, 'is_pegawai' => true, 'role' => 'pegawai'])
     */
    private function currentPegawaiId()
    {
        $id = session('user_id');
        if ($id) return (int) $id;

        // Fallback (darurat, agar tetap bisa testing):
        $firstPegawai = User::where('role', 'pegawai')->first();
        return $firstPegawai?->id ?? null;
    }

    public function index()
    {
        $pegawaiId = $this->currentPegawaiId();
        if (!$pegawaiId) {
            return back()->withErrors(['login' => 'Pegawai belum teridentifikasi. Set session user_id saat login.']);
        }

        $surat = SuratPerjalananDina::with(['pimpinan','rincianBiaya'])
                 ->where('pegawai_id', $pegawaiId)
                 ->latest('created_at')
                 ->get();

        $listPimpinan = User::where('role','pimpinan')->orderBy('name')->get();

        return view('pegawai.surat', compact('surat','listPimpinan'));
    }

    public function store(Request $request)
    {
        $pegawaiId = $this->currentPegawaiId();
        if (!$pegawaiId) return back()->withErrors(['login' => 'Pegawai belum teridentifikasi.']);

        $data = $request->validate([
            'pimpinan_id'       => ['required','exists:users,id'],
            'tanggal_berangkat' => ['required','date'],
            'tanggal_kembali'   => ['required','date','after_or_equal:tanggal_berangkat'],
            'tujuan'            => ['required','string','max:255'],
            'maksud_perjalanan' => ['nullable','string'],
            'alat_transportasi' => ['nullable','string','max:100'],

            // rincian
            'uang_harian'   => ['nullable','numeric','min:0'],
            'transportasi'  => ['nullable','numeric','min:0'],
        ]);

        $noSurat = $this->generateNoSurat();

        $spd = SuratPerjalananDina::create([
            'pegawai_id'         => $pegawaiId,
            'pimpinan_id'        => $data['pimpinan_id'],
            'no_surat'           => $noSurat,
            'tanggal_pengajuan'  => Carbon::now()->toDateString(),
            'tanggal_berangkat'  => $data['tanggal_berangkat'],
            'tanggal_kembali'    => $data['tanggal_kembali'],
            'tujuan'             => $data['tujuan'],
            'maksud_perjalanan'  => $data['maksud_perjalanan'] ?? null,
            'alat_transportasi'  => $data['alat_transportasi'] ?? null,
            'status'             => 'menunggu',
        ]);

        $uangHarian  = (float)($data['uang_harian'] ?? 0);
        $transport   = (float)($data['transportasi'] ?? 0);
        $total       = $uangHarian + $transport;

        RincianBiaya::create([
            'surat_id'     => $spd->id,
            'uang_harian'  => $uangHarian,
            'transportasi' => $transport,
            'jumlah_total' => $total,
            'terbilang'    => $this->terbilangRupiah($total),
        ]);

        LogAksi::create([
            'surat_id' => $spd->id,
            'user_id'  => $pegawaiId,
            'aksi'     => 'pengajuan',
            'keterangan' => 'Pengajuan dibuat oleh pegawai',
        ]);

        return back()->with('success','Pengajuan surat berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $pegawaiId = $this->currentPegawaiId();
        if (!$pegawaiId) return back()->withErrors(['login' => 'Pegawai belum teridentifikasi.']);

        $spd = SuratPerjalananDina::where('pegawai_id', $pegawaiId)->findOrFail($id);
        if (in_array($spd->status, ['diterima','ditolak'])) {
            return back()->withErrors(['update' => 'Surat sudah diproses, tidak bisa diubah.']);
        }

        $data = $request->validate([
            'pimpinan_id'       => ['required','exists:users,id'],
            'tanggal_berangkat' => ['required','date'],
            'tanggal_kembali'   => ['required','date','after_or_equal:tanggal_berangkat'],
            'tujuan'            => ['required','string','max:255'],
            'maksud_perjalanan' => ['nullable','string'],
            'alat_transportasi' => ['nullable','string','max:100'],

            // rincian
            'uang_harian'   => ['nullable','numeric','min:0'],
            'transportasi'  => ['nullable','numeric','min:0'],
        ]);

        $spd->update([
            'pimpinan_id'        => $data['pimpinan_id'],
            'tanggal_berangkat'  => $data['tanggal_berangkat'],
            'tanggal_kembali'    => $data['tanggal_kembali'],
            'tujuan'             => $data['tujuan'],
            'maksud_perjalanan'  => $data['maksud_perjalanan'] ?? null,
            'alat_transportasi'  => $data['alat_transportasi'] ?? null,
        ]);

        $uangHarian  = (float)($data['uang_harian'] ?? 0);
        $transport   = (float)($data['transportasi'] ?? 0);
        $total       = $uangHarian + $transport;

        $rb = RincianBiaya::firstOrNew(['surat_id' => $spd->id]);
        $rb->uang_harian  = $uangHarian;
        $rb->transportasi = $transport;
        $rb->jumlah_total = $total;
        $rb->terbilang    = $this->terbilangRupiah($total);
        $rb->save();

        LogAksi::create([
            'surat_id' => $spd->id,
            'user_id'  => $pegawaiId,
            'aksi'     => 'update',
            'keterangan' => 'Pengajuan diperbarui oleh pegawai',
        ]);

        return back()->with('success','Pengajuan surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pegawaiId = $this->currentPegawaiId();
        if (!$pegawaiId) return back()->withErrors(['login' => 'Pegawai belum teridentifikasi.']);

        $spd = SuratPerjalananDina::where('pegawai_id', $pegawaiId)->findOrFail($id);
        if ($spd->status !== 'menunggu') {
            return back()->withErrors(['delete' => 'Hanya surat berstatus menunggu yang bisa dihapus.']);
        }

        $spd->delete();

        return back()->with('success','Pengajuan surat berhasil dihapus.');
    }

    public function cetakPdf($id)
    {
        $pegawaiId = $this->currentPegawaiId();
        if (!$pegawaiId) return back()->withErrors(['login' => 'Pegawai belum teridentifikasi.']);

        $spd = SuratPerjalananDina::with(['pegawai','pimpinan','rincianBiaya'])
                ->where('pegawai_id',$pegawaiId)
                ->findOrFail($id);

        // Jika barryvdh/laravel-dompdf tersedia, pakai PDF; jika tidak, tampilkan HTML fallback
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pegawai.surat_pdf', compact('spd'));
            $filename = 'SPD-'.$spd->no_surat.'.pdf';
            return $pdf->download($filename);
        }

        // fallback HTML
        return view('pegawai.surat_pdf', compact('spd'));
    }

    private function generateNoSurat(): string
    {
        // Contoh format sederhana: SPPD/{YYYY}/{MM}/{random4}
        $seq = strtoupper(Str::random(4));
        return 'SPPD/'.date('Y').'/'.date('m').'/'.$seq;
    }

    private function terbilangRupiah($angka): string
    {
        // ringkas: format "Rp x.xxx.xxx,00" tanpa konversi kata panjang (cukup untuk tampilan)
        return 'Rupiah '.number_format($angka, 2, ',', '.');
    }
}
