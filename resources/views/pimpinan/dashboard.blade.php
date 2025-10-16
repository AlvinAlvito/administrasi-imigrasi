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
        <div class="row g-3">
            <div class="col-6 col-lg-3">
                <div class="card p-3">
                    <div class="fw-bold">Total Surat</div>
                    <div class="fs-3">{{ $total }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card p-3">
                    <div class="fw-bold">Menunggu</div>
                    <div class="fs-3">{{ $menunggu }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card p-3">
                    <div class="fw-bold">Diterima</div>
                    <div class="fs-3 text-success">{{ $diterima }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card p-3">
                    <div class="fw-bold">Ditolak</div>
                    <div class="fs-3 text-danger">{{ $ditolak }}</div>
                </div>
            </div>
        </div>

        <div class="activity mt-4">
            <div class="title">
                <i class="uil uil-clipboard-notes"></i>
                <span class="text">Pengajuan Terbaru</span>
            </div>

            <div class="text-end mb-2">
                <a href="{{ route('pimpinan.surat.index') }}" class="btn btn-sm btn-primary">
                    <i class="uil uil-check-circle"></i> Verifikasi Surat
                </a>
            </div>

            <table class="table table-hover table-striped align-middle">
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>Pegawai</th>
                        <th>Tujuan</th>
                        <th>Berangkat</th>
                        <th>Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $s)
                        @php $badge = ['menunggu'=>'secondary','diterima'=>'success','ditolak'=>'danger'][$s->status] ?? 'secondary'; @endphp
                        <tr>
                            <td>{{ $s->no_surat }}</td>
                            <td>{{ $s->pegawai->name ?? '-' }}</td>
                            <td>{{ $s->tujuan }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_berangkat)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_kembali)->format('d/m/Y') }}</td>
                            <td><span class="badge bg-{{ $badge }}">{{ ucfirst($s->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada pengajuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
