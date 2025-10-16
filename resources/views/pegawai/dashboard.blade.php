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
        {{-- Kartu ringkas --}}
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

        {{-- Aktivitas terbaru --}}
        <div class="activity mt-4">
            <div class="title">
                <i class="uil uil-clipboard-notes"></i>
                <span class="text">Pengajuan Terbaru</span>
            </div>

            <div class="text-end mb-2">
                <a href="{{ route('pegawai.surat.index') }}" class="btn btn-sm btn-primary">
                    <i class="uil uil-file-plus-alt"></i> Kelola Surat Saya
                </a>
            </div>

            <table class="table table-hover table-striped align-middle">
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>Pimpinan</th>
                        <th>Tujuan</th>
                        <th>Berangkat</th>
                        <th>Kembali</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $s)
                        @php $badge = ['menunggu'=>'secondary','diterima'=>'success','ditolak'=>'danger'][$s->status] ?? 'secondary'; @endphp
                        <tr>
                            <td>{{ $s->no_surat }}</td>
                            <td>{{ $s->pimpinan->name ?? '-' }}</td>
                            <td>{{ $s->tujuan }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_berangkat)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggal_kembali)->format('d/m/Y') }}</td>
                            <td><span class="badge bg-{{ $badge }}">{{ ucfirst($s->status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('pegawai.surat.pdf', $s->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="uil uil-file-download"></i> Cetak
                                </a>
                                <a href="{{ route('pegawai.surat.index') }}" class="btn btn-sm btn-link">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada pengajuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
