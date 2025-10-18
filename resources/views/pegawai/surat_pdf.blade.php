@php
    use Carbon\Carbon;

    /* ================= Helper ================= */
    function tanggal_indo($date) {
        if(!$date) return '-';
        $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $d = Carbon::parse($date);
        return $d->format('d').' '.$bulan[(int)$d->format('m')].' '.$d->format('Y');
    }
    function rupiah($angka) { return 'Rp '.number_format((float)$angka, 0, ',', '.').',-'; }

    /* ================= Data ================= */
    $rb          = $spd->rincianBiaya;
    $uangHarian  = $rb->uang_harian ?? 0;
    $transport   = $rb->transportasi ?? 0;
    $total       = $rb->jumlah_total ?? ($uangHarian + $transport);
    $terbilang   = $rb->terbilang ?? '-';

    $kotaInstansi    = 'Tanjungbalai';
    $namaBendahara   = 'PAIAN MARTUA R. SILITONGA';
    $nipBendahara    = '198709242010121003';
    $namaPPK         = $spd->pimpinan->name ?? 'BARANDARU WIDYARTO';
    $nipPPK          = $spd->pimpinan->nip ?? '197802181999121001';
    $jabatanPimpinan = $spd->pimpinan->jabatan ?? 'KEPALA KANTOR IMIGRASI';

    /* ================= Gambar -> Base64 ================= */
    // Logo Garuda (public/images/garuda.png)
    $logoData = null;
    $logoAbs = public_path('images/garuda.png');
    if (file_exists($logoAbs)) {
        $ext  = strtolower(pathinfo($logoAbs, PATHINFO_EXTENSION)); // png/jpg/jpeg
        $mime = $ext === 'jpg' ? 'image/jpeg' : 'image/'.$ext;
        $logoData = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($logoAbs));
    }

    // TTD pimpinan (storage/app/public/…)
    $ttdData = null;
    $ttdRel = optional(optional($spd->pimpinan)->pimpinanProfile)->tanda_tangan;
    if ($ttdRel) {
        $ttdAbs = public_path('storage/'.$ttdRel);
        if (file_exists($ttdAbs)) {
            $ext  = strtolower(pathinfo($ttdAbs, PATHINFO_EXTENSION));
            $mime = $ext === 'jpg' ? 'image/jpeg' : 'image/'.$ext;
            $ttdData = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($ttdAbs));
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>SPD {{ $spd->no_surat }}</title>
<style>
    @page { margin: 28px 28px 36px 28px; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color:#000; }
    .text-center { text-align:center; }
    .text-right { text-align:right; }
    .fw-bold { font-weight: bold; }
    .mb-1{ margin-bottom:6px; } .mb-2{ margin-bottom:12px;} .mb-3{ margin-bottom:18px;}
    table { width:100%; border-collapse: collapse; }
    td, th { padding:8px; vertical-align: top; }
    .border { border:1px solid #000; }
    .no-border { border:0; }
    .w-50{ width:50%; } .w-60{ width:60%; } .w-40{ width:40%; }
    .page-break { page-break-after: always; }
    .table-tight td, .table-tight th { padding:6px; }
    .underline { text-decoration: underline; }
    .ttd-box { height:90px; }
    .logo { width:78px; }

    /* ======= Header Lampiran kanan atas ======= */
/* ======= Header Lampiran kanan atas (anti mepet) ======= */
.lampiran-header-wrap{
    position: relative;
    height: 140px;
    padding-top: 32px;   /* ✅ dorong isi turun tanpa collapse */
}
.lampiran-logo{
    text-align: center;
}
.lampiran-logo img{
    display: block;      /* ✅ cegah margin collapse */
    margin: 0 auto;
}
.lampiran-right{
    position: absolute;
    top: 8px;            /* geser blok judul dari tepi atas */
    right: 0;
    text-align: right;
    line-height: 1.2;
}
.lampiran-right .l1{ font-weight: bold; }
.lampiran-right .l2, .lampiran-right .l3{ font-weight: bold; }
.lampiran-right .l4{ font-size: 11px; }

</style>
</head>
<body>

{{-- ================= HALAMAN 1: SPPD RINGKAS ================= --}}
<h3 class="text-center" style="margin-top:10px;">SURAT PERJALANAN DINAS</h3>
<p class="text-center mb-2">No: {{ $spd->no_surat }}</p>

<table class="border table-tight">
    <tr>
        <td class="border w-50"><span class="fw-bold">Pegawai</span><br>{{ $spd->pegawai->name ?? '-' }}</td>
        <td class="border w-50"><span class="fw-bold">Pimpinan</span><br>{{ $spd->pimpinan->name ?? '-' }} ({{ $jabatanPimpinan }})</td>
    </tr>
    <tr>
        <td class="border"><span class="fw-bold">Tanggal Berangkat</span><br>{{ tanggal_indo($spd->tanggal_berangkat) }}</td>
        <td class="border"><span class="fw-bold">Tanggal Kembali</span><br>{{ tanggal_indo($spd->tanggal_kembali) }}</td>
    </tr>
    <tr>
        <td class="border"><span class="fw-bold">Tujuan</span><br>{{ $spd->tujuan }}</td>
        <td class="border"><span class="fw-bold">Transportasi</span><br>{{ $spd->alat_transportasi ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" class="border">
            <span class="fw-bold">Maksud Perjalanan</span><br>
            {!! nl2br(e($spd->maksud_perjalanan)) !!}
        </td>
    </tr>
</table>

<h4 class="mb-1">Rincian Biaya</h4>
<table class="border table-tight">
    <tr>
        <th class="border w-25">Uang Harian</th>
        <th class="border w-25">Transportasi</th>
        <th class="border w-25">Total</th>
        <th class="border w-25">Terbilang</th>
    </tr>
    <tr>
        <td class="border">{{ rupiah($uangHarian) }}</td>
        <td class="border">{{ rupiah($transport) }}</td>
        <td class="border">{{ rupiah($total) }}</td>
        <td class="border">{{ $terbilang }}</td>
    </tr>
</table>

<p class="mb-3">Status: <span class="fw-bold">{{ ucfirst($spd->status) }}</span></p>

<table>
    <tr>
        <td class="w-60"></td>
        <td class="w-40 text-center">
            <div>Disahkan oleh,</div>
            <div class="fw-bold">{{ strtoupper($jabatanPimpinan) }}</div>
            <div class="ttd-box">
                @if($ttdData)
                    <img src="{{ $ttdData }}" alt="TTD" style="height:80px;">
                @endif
            </div>
            <div class="fw-bold underline">{{ $spd->pimpinan->name ?? '-' }}</div>
            <div>NIP: {{ $spd->pimpinan->nip ?? '-' }}</div>
        </td>
    </tr>
</table>

<div class="page-break"></div>

{{-- =============== HALAMAN 2: LAMPIRAN II (RINCIAN BIAYA) =============== --}}
<div class="lampiran-header-wrap">
    {{-- Logo di tengah atas --}}
    <div class="lampiran-logo">
        @if($logoData)
            <img src="{{ $logoData }}" class="logo" alt="Garuda">
        @endif
    </div>
    {{-- Blok judul sudut kanan atas --}}
    <div class="lampiran-right">
        <div class="l1">LAMPIRAN II</div>
        <div class="l2">PERATURAN MENTERI KEUANGAN REPUBLIK INDONESIA</div>
        <div class="l3">NOMOR 113/PMK.05/2012</div>
        <div class="l4">TENTANG PERJALANAN DINAS JABATAN DALAM NEGERI BAGI PEJABAT NEGARA,<br>PEGAWAI NEGERI DAN PEGAWAI TIDAK TETAP</div>
    </div>
</div>

<h4 class="text-center mb-2" style="margin-top:6px;">RINCIAN BIAYA PERJALANAN DINAS</h4>

<table class="no-border mb-2" style="font-size:12px;">
    <tr>
        <td class="w-50">Lampiran SPD Nomor : <span class="fw-bold">{{ $spd->no_surat }}</span></td>
        <td class="w-50">Tanggal : <span class="fw-bold">{{ tanggal_indo($spd->tanggal_berangkat) }}</span></td>
    </tr>
</table>

<table class="border table-tight">
    <tr>
        <th class="border" style="width:40px;">NO</th>
        <th class="border">PERINCIAN BIAYA</th>
        <th class="border" style="width:160px;">JUMLAH</th>
        <th class="border" style="width:240px;">KETERANGAN</th>
    </tr>
    <tr>
        <td class="border text-center">1.</td>
        <td class="border">Uang Harian</td>
        <td class="border">{{ rupiah($uangHarian) }}</td>
        <td class="border">1 Hari x {{ rupiah($uangHarian) }}</td>
    </tr>
    <tr>
        <td class="border text-center">2.</td>
        <td class="border">Taksi</td>
        <td class="border">{{ rupiah($transport) }}</td>
        <td class="border">
            Tanjungbalai x {{ rupiah($transport/2) }}<br>
            Batubara x {{ rupiah($transport/2) }}
        </td>
    </tr>
    <tr>
        <td class="border fw-bold text-center" colspan="2">Jumlah</td>
        <td class="border fw-bold">{{ rupiah($total) }}</td>
        <td class="border"></td>
    </tr>
    <tr>
        <td class="border fw-bold" colspan="1">Terbilang</td>
        <td class="border" colspan="3">{{ $terbilang }}</td>
    </tr>
</table>

<table class="no-border" style="margin-top:14px;">
    <tr>
        <td class="w-50">
            Telah dibayar sejumlah<br>
            <span class="fw-bold">{{ rupiah($total) }}</span><br>
            Bendahara Pengeluaran
            <div class="ttd-box"></div>
            <div class="fw-bold underline">{{ $namaBendahara }}</div>
            <div>NIP. {{ $nipBendahara }}</div>
        </td>
        <td class="w-50 text-right">
            {{ $kotaInstansi }},  {{ tanggal_indo($spd->tanggal_berangkat) }}<br>
            Telah menerima jumlah Uang sebesar<br>
            <span class="fw-bold">{{ rupiah($total) }}</span><br>
            Yang Menerima,
            <div class="ttd-box"></div>
            <div class="fw-bold underline">{{ $spd->pegawai->name ?? '-' }}</div>
            <div>NIP. {{ $spd->pegawai->nip ?? '-' }}</div>
        </td>
    </tr>
</table>

<hr style="margin: 20px 0;">

<div class="text-center fw-bold mb-1">PERHITUNGAN SPD RAMPUNG</div>
<table class="no-border" style="font-size:12px;">
    <tr>
        <td class="w-60">Ditetapkan sejumlah</td>
        <td class="w-40">: {{ rupiah($total) }}</td>
    </tr>
    <tr>
        <td>Yang telah dibayar semula</td>
        <td>: {{ rupiah($total) }}</td>
    </tr>
    <tr>
        <td>Sisa kurang / lebih</td>
        <td>: Rp. –</td>
    </tr>
</table>

<table class="no-border" style="margin-top:26px;">
    <tr>
        <td class="w-60"></td>
        <td class="w-40 text-center">
            Pejabat Pembuat Komitmen
            <div class="ttd-box">
                @if($ttdData)
                    <img src="{{ $ttdData }}" alt="TTD" style="height:80px;">
                @endif
            </div>
            <div class="fw-bold underline">{{ $namaPPK }}</div>
            <div>NIP. {{ $nipPPK }}</div>
        </td>
    </tr>
</table>

</body>
</html>
