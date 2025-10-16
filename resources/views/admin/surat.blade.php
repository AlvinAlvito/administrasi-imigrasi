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
                <i class="uil uil-clipboard-notes"></i>
                <span class="text">Daftar Semua Surat Perjalanan Dinas</span>
            </div>

            <table id="datatable" class="table table-hover table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Surat</th>
                        <th>Pegawai</th>
                        <th>Pimpinan</th>
                        <th>Tujuan</th>
                        <th>Tgl Berangkat</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Total Biaya</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surat as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->no_surat }}</td>
                            <td>{{ $s->pegawai->name ?? '-' }}</td>
                            <td>{{ $s->pimpinan->name ?? '-' }}</td>
                            <td>{{ $s->tujuan }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_berangkat)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_kembali)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $badge = ['menunggu'=>'secondary','diterima'=>'success','ditolak'=>'danger'][$s->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($s->status) }}</span>
                            </td>
                            <td>
                                @if ($s->rincianBiaya)
                                    Rp {{ number_format($s->rincianBiaya->jumlah_total,0,',','.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($s->file_surat_pdf)
                                    <a href="{{ asset($s->file_surat_pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="uil uil-file-download"></i> Lihat
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">Belum ada surat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>$(function(){ $('#datatable').DataTable(); });</script>
@endsection
