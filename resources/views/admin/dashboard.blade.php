@extends('layouts.main')

@section('content')
@php
    // Siapkan koleksi untuk tabel (aman jika controller belum kirim)
    $pegawaiList  = $pegawaiList  ?? ($pegawai  ?? collect());
    $pimpinanList = $pimpinanList ?? collect();
@endphp

<style>
    /* ====== Hero & Cards Styling ====== */
    .profile-hero{
        background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        border-radius: 18px;
        padding: 24px 20px;
        color: #fff;
        position: relative; overflow: hidden; margin-top: 8px;
    }
    .profile-hero::after{
        content:''; position:absolute; right:-60px; top:-60px;
        width:180px; height:180px; background: rgba(255,255,255,0.15);
        filter: blur(8px); border-radius: 50%;
    }
    .hero-title{ font-size: 20px; font-weight: 800; letter-spacing:.3px; }
    .hero-sub{ opacity:.95; }

    .card{
        border: 1px solid #eef0f4;
        border-radius: 16px;
        box-shadow: 0 6px 14px rgba(20,37,63,.06);
        height: 100%;
        transition: all .2s ease-in-out;
    }
    .card:hover{ transform: translateY(-3px); box-shadow: 0 10px 24px rgba(20,37,63,.08); }

    .icon-wrapper {
        width: 56px; height: 56px;
        display:flex; align-items:center; justify-content:center;
        border-radius: 12px;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);
    }
    .stat-title{ color:#64748b; font-weight:700; font-size:13px; }
    .stat-value{ font-size:28px; font-weight:800; color:#0f172a; }
    .stat-acc{ color:#16a34a; } .stat-rej{ color:#dc2626; }

    .section-title{
        font-size: 14px; text-transform: uppercase; letter-spacing: .12em;
        color:#64748b; font-weight: 800; margin-bottom: 12px;
    }
    .table thead th{ font-weight:700; color:#334155; }
    .badge{ font-weight:700; letter-spacing:.2px; }
</style>

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

        {{-- HERO --}}
        <div class="profile-hero mb-4">
            <div class="row align-items-center g-3">
                <div class="col">
                    <div class="hero-title">Dashboard Admin</div>
                    <div class="hero-sub">Ringkasan data pengguna & pengajuan surat perjalanan dinas</div>
                </div>
                <div class="col-12 col-md-auto text-md-end mt-2 mt-md-0">
                    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-light me-2">
                        <i class="uil uil-users-alt"></i> Kelola Pegawai
                    </a>
                    <a href="{{ route('admin.pimpinan.index') }}" class="btn btn-light">
                        <i class="uil uil-user-check"></i> Kelola Pimpinan
                    </a>
                </div>
            </div>
        </div>

        {{-- KARTU STATISTIK --}}
        <div class="row g-3">
            <div class="col-6 col-lg-2">
                <div class="card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="icon-wrapper bg-info bg-opacity-10 text-info">
                        <i class="uil uil-users-alt fs-3"></i>
                    </div>
                    <div>
                        <div class="stat-title">Pegawai</div>
                        <div class="stat-value">{{ $totalPegawai }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="icon-wrapper bg-success bg-opacity-10 text-success">
                        <i class="uil uil-user-check fs-3"></i>
                    </div>
                    <div>
                        <div class="stat-title">Pimpinan</div>
                        <div class="stat-value">{{ $totalPimpinan }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary">
                        <i class="uil uil-file-alt fs-3"></i>
                    </div>
                    <div>
                        <div class="stat-title">Surat</div>
                        <div class="stat-value">{{ $totalSurat }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="icon-wrapper bg-warning bg-opacity-10 text-warning">
                        <i class="uil uil-history fs-3"></i>
                    </div>
                    <div>
                        <div class="stat-title">Menunggu</div>
                        <div class="stat-value">{{ $menunggu }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="icon-wrapper bg-success bg-opacity-10 text-success">
                        <i class="uil uil-check-circle fs-3"></i>
                    </div>
                    <div>
                        <div class="stat-title">Diterima</div>
                        <div class="stat-value stat-acc">{{ $diterima }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="icon-wrapper bg-danger bg-opacity-10 text-danger">
                        <i class="uil uil-times-circle fs-3"></i>
                    </div>
                    <div>
                        <div class="stat-title">Ditolak</div>
                        <div class="stat-value stat-rej">{{ $ditolak }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DAFTAR PEGAWAI & PIMPINAN --}}
        <div class="row g-4 mt-4">
            {{-- Pegawai --}}
            <div class="col-lg-6">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="section-title"><i class="uil uil-users-alt"></i> Daftar Pegawai</div>
                        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-sm btn-soft">
                            Kelola
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Eselon</th>
                                    <th>Pangkat/Gol</th>
                                    <th>TMT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pegawaiList as $pgw)
                                    @php $pr = $pgw->pegawaiProfile ?? null; @endphp
                                    <tr>
                                        <td>{{ $pgw->nip ?? '-' }}</td>
                                        <td class="fw-semibold">{{ $pgw->name }}</td>
                                        <td>{{ $pr->jabatan ?? '-' }}</td>
                                        <td>{{ $pr->eselon ?? '-' }}</td>
                                        <td>{{ $pr->pangkat_gol ?? '-' }}</td>
                                        <td>
                                            @if(!empty($pr?->tmt))
                                                {{ \Carbon\Carbon::parse($pr->tmt)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">Belum ada data pegawai.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pimpinan --}}
            <div class="col-lg-6">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="section-title"><i class="uil uil-user-check"></i> Daftar Pimpinan</div>
                        <a href="{{ route('admin.pimpinan.index') }}" class="btn btn-sm btn-soft">
                            Kelola
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Eselon</th>
                                    <th>Pangkat/Gol</th>
                                    <th>TMT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pimpinanList as $pmp)
                                    @php $pp = $pmp->pimpinanProfile ?? null; @endphp
                                    <tr>
                                        <td>{{ $pmp->nip ?? '-' }}</td>
                                        <td class="fw-semibold">{{ $pmp->name }}</td>
                                        <td>{{ $pp->jabatan ?? '-' }}</td>
                                        <td>{{ $pp->eselon ?? '-' }}</td>
                                        <td>{{ $pp->pangkat_gol ?? '-' }}</td>
                                        <td>
                                            @if(!empty($pp?->tmt))
                                                {{ \Carbon\Carbon::parse($pp->tmt)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">Belum ada data pimpinan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
