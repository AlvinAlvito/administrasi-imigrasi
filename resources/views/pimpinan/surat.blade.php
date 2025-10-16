@extends('layouts.main')
@section('content')
<section class="dashboard">
    <div class="top">
        <i class="uil uil-bars sidebar-toggle"></i>
        <div class="search-box">
            <i class="uil uil-search"></i>
            <input type="text" placeholder="Search here...">
        </div>
        <img src="/images/profil.png" alt="">
    </div>

    <div class="dash-content">
        <div class="activity">
            <div class="title">
                <i class="uil uil-check-circle"></i>
                <span class="text">Verifikasi Surat</span>
            </div>

            @if (session('success'))
                <div class="alert alert-success mt-2">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mt-2">
                    <ul class="m-0 ps-3">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <table id="datatable" class="table table-hover table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Surat</th>
                        <th>Pegawai</th>
                        <th>Tujuan</th>
                        <th>Berangkat</th>
                        <th>Kembali</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surat as $s)
                        @php
                            $badge = ['menunggu'=>'secondary','diterima'=>'success','ditolak'=>'danger'][$s->status] ?? 'secondary';
                            $total = $s->rincianBiaya->jumlah_total ?? 0;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->no_surat }}</td>
                            <td>{{ $s->pegawai->name ?? '-' }}</td>
                            <td>{{ $s->tujuan }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_berangkat)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_kembali)->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($total,0,',','.') }}</td>
                            <td><span class="badge bg-{{ $badge }}">{{ ucfirst($s->status) }}</span></td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#modalDetail{{ $s->id }}">
                                    Detail
                                </button>

                                @if ($s->status === 'menunggu')
                                    <form action="{{ route('pimpinan.surat.approve', $s->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success"
                                                onclick="return confirm('Terima surat ini?')">
                                            Terima
                                        </button>
                                    </form>

                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalTolak{{ $s->id }}">
                                        Tolak
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center">Belum ada pengajuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Modal Detail --}}
@foreach ($surat as $s)
<div class="modal fade" id="modalDetail{{ $s->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Surat — {{ $s->no_surat }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Pegawai</dt>
                    <dd class="col-sm-8">{{ $s->pegawai->name ?? '-' }}</dd>

                    <dt class="col-sm-4">Tujuan</dt>
                    <dd class="col-sm-8">{{ $s->tujuan }}</dd>

                    <dt class="col-sm-4">Maksud Perjalanan</dt>
                    <dd class="col-sm-8">{!! nl2br(e($s->maksud_perjalanan)) !!}</dd>

                    <dt class="col-sm-4">Alat Transportasi</dt>
                    <dd class="col-sm-8">{{ $s->alat_transportasi ?? '-' }}</dd>

                    <dt class="col-sm-4">Tanggal Berangkat</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($s->tanggal_berangkat)->format('d/m/Y') }}</dd>

                    <dt class="col-sm-4">Tanggal Kembali</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($s->tanggal_kembali)->format('d/m/Y') }}</dd>

                    <dt class="col-sm-4">Rincian Biaya</dt>
                    <dd class="col-sm-8">
                        Uang Harian: Rp {{ number_format($s->rincianBiaya->uang_harian ?? 0,0,',','.') }}<br>
                        Transportasi: Rp {{ number_format($s->rincianBiaya->transportasi ?? 0,0,',','.') }}<br>
                        Total: <strong>Rp {{ number_format($s->rincianBiaya->jumlah_total ?? 0,0,',','.') }}</strong><br>
                        Terbilang: {{ $s->rincianBiaya->terbilang ?? '-' }}
                    </dd>

                    <dt class="col-sm-4">Catatan Pimpinan</dt>
                    <dd class="col-sm-8">{{ $s->catatan_pimpinan ?? '-' }}</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <a href="{{ route('pegawai.surat.pdf', $s->id) }}" class="btn btn-outline-primary" target="_blank">
                    <i class="uil uil-file-download"></i> Lihat/Cetak
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- Modal Tolak --}}
@foreach ($surat as $s)
<div class="modal fade" id="modalTolak{{ $s->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pimpinan.surat.reject', $s->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tolak Surat — {{ $s->no_surat }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label>Catatan (opsional)</label>
                <textarea name="catatan_pimpinan" class="form-control" rows="3"
                          placeholder="Alasan penolakan atau catatan"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger">Tolak</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>$(function(){ $('#datatable').DataTable(); });</script>
@endsection
