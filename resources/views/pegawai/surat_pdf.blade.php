<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SPD {{ $spd->no_surat }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        .mb-1 { margin-bottom: 6px; }
        .mb-2 { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 6px; vertical-align: top; }
        .border { border: 1px solid #000; }
        .w-50 { width: 50%; }
    </style>
</head>
<body>
    <h3 class="text-center">SURAT PERJALANAN DINAS</h3>
    <p class="text-center mb-2">No: {{ $spd->no_surat }}</p>

    <table class="border">
        <tr>
            <td class="w-50 border"><strong>Pegawai</strong><br>{{ $spd->pegawai->name ?? '-' }}</td>
            <td class="w-50 border"><strong>Pimpinan</strong><br>{{ $spd->pimpinan->name ?? '-' }} ({{ $spd->pimpinan->jabatan ?? '-' }})</td>
        </tr>
        <tr>
            <td class="border"><strong>Tanggal Berangkat</strong><br>{{ \Carbon\Carbon::parse($spd->tanggal_berangkat)->format('d/m/Y') }}</td>
            <td class="border"><strong>Tanggal Kembali</strong><br>{{ \Carbon\Carbon::parse($spd->tanggal_kembali)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="border"><strong>Tujuan</strong><br>{{ $spd->tujuan }}</td>
            <td class="border"><strong>Transportasi</strong><br>{{ $spd->alat_transportasi ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="border">
                <strong>Maksud Perjalanan</strong><br>
                {!! nl2br(e($spd->maksud_perjalanan)) !!}
            </td>
        </tr>
    </table>

    <h4 class="mb-1">Rincian Biaya</h4>
    <table class="border">
        <tr>
            <th class="border">Uang Harian</th>
            <th class="border">Transportasi</th>
            <th class="border">Total</th>
            <th class="border">Terbilang</th>
        </tr>
        <tr>
            <td class="border">Rp {{ number_format($spd->rincianBiaya->uang_harian ?? 0, 0, ',', '.') }}</td>
            <td class="border">Rp {{ number_format($spd->rincianBiaya->transportasi ?? 0, 0, ',', '.') }}</td>
            <td class="border">Rp {{ number_format($spd->rincianBiaya->jumlah_total ?? 0, 0, ',', '.') }}</td>
            <td class="border">{{ $spd->rincianBiaya->terbilang ?? '-' }}</td>
        </tr>
    </table>

    <p class="mb-2">Status: <strong>{{ ucfirst($spd->status) }}</strong></p>

    <table style="margin-top:40px;">
        <tr>
            <td class="w-50"></td>
            <td class="w-50">
                <div class="text-center">
                    <div>Disahkan oleh,</div>
                    <div><strong>{{ $spd->pimpinan->jabatan ?? 'Pimpinan' }}</strong></div>
                    <br><br><br>
                    <div><strong>{{ $spd->pimpinan->name ?? '-' }}</strong></div>
                    <div>NIP: {{ $spd->pimpinan->nip ?? '-' }}</div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
