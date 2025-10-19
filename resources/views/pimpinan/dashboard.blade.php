@extends('layouts.main')
@section('content')
@php
    // ambil nama pimpinan dari session user_id
    $current = \App\Models\User::find(session('user_id'));
    $displayName = $current->name ?? 'Pimpinan';

    // avatar inisial
    $parts = preg_split('/\s+/', trim($displayName));
    $initials = '';
    foreach ($parts as $i => $part) { if ($i < 2 && strlen($part)) $initials .= mb_strtoupper(mb_substr($part,0,1)); }
    if ($initials === '') $initials = 'PM';
@endphp

<style>
    /* ====== Hero Styling (selaras profil) ====== */
    .profile-hero{
        background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
        border-radius: 18px;
        padding: 24px 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-top: 8px;
    }
    .profile-hero::after{
        content:'';
        position:absolute; right:-60px; top:-60px;
        width:180px; height:180px;
        background: rgba(255,255,255,0.15);
        filter: blur(8px); border-radius: 50%;
    }
    .avatar{
        width: 70px; height: 70px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
        display:flex; align-items:center; justify-content:center;
        font-weight: 700; font-size: 24px;
        backdrop-filter: blur(2px);
        border: 2px solid rgba(255,255,255,0.35);
    }
    .hero-name{ font-size: 20px; font-weight: 700; letter-spacing:.2px; }
    .hero-sub{ opacity:.95; }
    .chip{
        display:inline-block; padding:6px 10px; border-radius:999px;
        background: rgba(255,255,255,0.18); color:#fff; font-size:12px; font-weight:600;
        border:1px solid rgba(255,255,255,0.35);
        margin-right:6px;
    }

    /* ====== Cards & table ====== */
    .card{
        border: 1px solid #eef0f4;
        border-radius: 16px;
        box-shadow: 0 6px 14px rgba(20,37,63,.06);
        height: 100%;
    }
    .stat-title{ color:#64748b; font-weight: 700; font-size: 13px; }
    .stat-value{ font-size: 28px; font-weight: 800; color:#0f172a; }
    .stat-acc{ color:#16a34a; }
    .stat-rej{ color:#dc2626; }

    .activity .title .text{ font-weight: 800; letter-spacing: .3px; }

    .btn-soft{
        border-radius: 10px; border:1px solid #e8ecf2; background:#f8fafc; color:#0f172a; font-weight:600;
    }
    .btn-soft:hover{ background:#eef3f9; }

    .table thead th{ font-weight:700; color:#334155; }
    .badge{ font-weight: 700; letter-spacing:.2px; }
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
                <div class="col-auto">
                    <div class="avatar">{{ $initials }}</div>
                </div>
                <div class="col">
                    <div class="hero-name">{{ $displayName }}</div>
                    <div class="hero-sub">Ringkasan persetujuan surat perjalanan dinas</div>
                    <div class="mt-2">
                        <span class="chip"><i class="uil uil-user"></i> Pimpinan</span>
                        <span class="chip"><i class="uil uil-check-circle"></i> Verifikasi & Persetujuan</span>
                    </div>
                </div>
                <div class="col-12 col-md-auto text-md-end mt-2 mt-md-0">
                    <a href="{{ route('pimpinan.surat.index') }}" class="btn btn-light">
                        <i class="uil uil-check-circle"></i> Buka Halaman Verifikasi
                    </a>
                </div>
            </div>
        </div>

        {{-- Kartu ringkas --}}
      <div class="row g-3">
    <div class="col-6 col-lg-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="icon-wrapper bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                <i class="uil uil-file-alt fs-3"></i>
            </div>
            <div>
                <div class="stat-title">Total Surat</div>
                <div class="stat-value">{{ $total }}</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="icon-wrapper bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                <i class="uil uil-history fs-3"></i>
            </div>
            <div>
                <div class="stat-title">Menunggu</div>
                <div class="stat-value">{{ $menunggu }}</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-3 p-3">
                <i class="uil uil-check-circle fs-3"></i>
            </div>
            <div>
                <div class="stat-title">Diterima</div>
                <div class="stat-value stat-acc">{{ $diterima }}</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="icon-wrapper bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                <i class="uil uil-times-circle fs-3"></i>
            </div>
            <div>
                <div class="stat-title">Ditolak</div>
                <div class="stat-value stat-rej">{{ $ditolak }}</div>
            </div>
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
                <a href="{{ route('pimpinan.surat.index') }}" class="btn btn-sm btn-primary">
                    <i class="uil uil-check-circle"></i> Verifikasi Surat
                </a>
            </div>

            <div class="card p-3">
                <table class="table table-hover table-striped align-middle mb-0">
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
                                <td class="fw-semibold">{{ $s->no_surat }}</td>
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
    </div>
</section>
@endsection
