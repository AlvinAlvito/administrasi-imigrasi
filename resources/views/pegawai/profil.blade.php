@extends('layouts.main')

@section('content')
@php
    // helper kecil buat inisial avatar
    $displayName = $user->name ?? '-';
    $parts = preg_split('/\s+/', trim($displayName));
    $initials = '';
    foreach ($parts as $i => $part) { if ($i < 2 && strlen($part)) $initials .= mb_strtoupper(mb_substr($part,0,1)); }
    if ($initials === '') $initials = 'PG';

    $gender = $p->jenis_kelamin ?? '-';
    $tmtFormatted = !empty($p?->tmt) ? \Carbon\Carbon::parse($p->tmt)->format('d/m/Y') : '-';
@endphp

<style>
    /* ====== Profil Polishing ====== */
    .profile-hero{
        background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        border-radius: 18px;
        padding: 28px 22px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .profile-hero::after{
        content:'';
        position:absolute; right:-60px; top:-60px;
        width:180px; height:180px;
        background: rgba(255,255,255,0.15);
        filter: blur(8px); border-radius: 50%;
    }
    .avatar{
        width: 84px; height: 84px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
        display:flex; align-items:center; justify-content:center;
        font-weight: 700; font-size: 28px;
        backdrop-filter: blur(2px);
        border: 2px solid rgba(255,255,255,0.35);
    }
    .hero-name{ font-size: 22px; font-weight: 700; letter-spacing:.2px; }
    .hero-sub{ opacity:.95; }
    .chip{
        display:inline-block; padding:6px 10px; border-radius:999px;
        background: rgba(255,255,255,0.18); color:#fff; font-size:12px; font-weight:600;
        border:1px solid rgba(255,255,255,0.35);
        margin-right:6px;
    }

    .card{
        border: 1px solid #eef0f4;
        border-radius: 16px;
        box-shadow: 0 6px 14px rgba(20,37,63,.06);
    }
    .section-title{
        font-size: 14px; text-transform: uppercase; letter-spacing: .12em; color:#64748b;
        font-weight: 700; margin-bottom: 12px;
    }
    .info-grid{
        display:grid; grid-template-columns: 1fr 1.5fr; gap:10px 18px;
    }
    @media (max-width: 768px){ .info-grid{ grid-template-columns: 1fr; } }

    .label{ color:#64748b; font-weight:600; }
    .value{ color:#0f172a; font-weight:600; }

    .divider{ height:1px; background:#eef0f4; margin: 14px 0; }

    .kicker{
        display:flex; gap:12px; flex-wrap:wrap;
    }
    .kicker .stat{
        display:flex; align-items:center; gap:8px;
        padding:8px 12px; border-radius: 12px; background:#f8fafc; border:1px solid #eef0f4;
        font-weight:600; color:#0f172a;
    }
    .kicker i{ color:#6366f1; }

    .btn-soft{
        border-radius: 10px; border:1px solid #e8ecf2; background:#f8fafc; color:#0f172a; font-weight:600;
    }
    .btn-soft:hover{ background:#eef3f9; }
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
        <div class="profile-hero mb-4 mt-3">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <div class="avatar">{{ $initials }}</div>
                </div>
                <div class="col">
                    <div class="hero-name">{{ $displayName }}</div>
                    <div class="hero-sub">NIP: <strong>{{ $user->nip ?? '-' }}</strong></div>
                    <div class="mt-2">
                        <span class="chip"><i class="uil uil-user"></i> Pegawai</span>
                        <span class="chip"><i class="uil uil-venus-mars"></i> {{ $gender }}</span>
                    </div>
                </div>
                <div class="col-12 col-md-auto text-md-end mt-2 mt-md-0">
                    <div class="kicker">
                        <div class="stat"><i class="uil uil-suitcase"></i> {{ $p->jabatan ?? '—' }}</div>
                        <div class="stat"><i class="uil uil-books"></i> {{ $p->pendidikan ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card p-3">
                    <div class="section-title"><i class="uil uil-id-badge"></i> Identitas</div>
                    <div class="info-grid">
                        <div class="label">Nama</div>              <div class="value">{{ $user->name }}</div>
                        <div class="label">NIP</div>               <div class="value">{{ $user->nip ?? '-' }}</div>
                        <div class="label">Jenis Kelamin</div>     <div class="value">{{ $gender }}</div>
                        <div class="label">Jabatan</div>           <div class="value">{{ $p->jabatan ?? '-' }}</div>
                        <div class="label">Eselon</div>            <div class="value">{{ $p->eselon ?? '-' }}</div>
                        <div class="label">Pangkat / Gol</div>     <div class="value">{{ $p->pangkat_gol ?? '-' }}</div>
                        <div class="label">TMT</div>               <div class="value">{{ $tmtFormatted }}</div>
                    </div>

                    <div class="divider"></div>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('pegawai.surat.index') }}" class="btn btn-primary">
                            <i class="uil uil-file-plus-alt"></i> Kelola Surat Saya
                        </a>
                        <a href="{{ route('pegawai.index') }}" class="btn btn-soft">
                            <i class="uil uil-estate"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card p-3 h-100">
                    <div class="section-title"><i class="uil uil-graduation-cap"></i> Pendidikan & Diklat</div>
                    <div class="info-grid">
                        <div class="label">Pendidikan</div>        <div class="value">{{ $p->pendidikan ?? '-' }}</div>
                        <div class="label">Diklat Teknis</div>      <div class="value">{{ $p->diklat_teknis ?? '-' }}</div>
                    </div>

                    <div class="divider"></div>

                    <div class="section-title mb-2"><i class="uil uil-clipboard-notes"></i> Ringkasan</div>
                    <ul class="mb-0 ps-3" style="color:#0f172a; font-weight:600; line-height:1.4;">
                        <li>Jabatan: {{ $p->jabatan ?? '-' }}</li>
                        <li>Eselon: {{ $p->eselon ?? '-' }}</li>
                        <li>Pangkat/Gol: {{ $p->pangkat_gol ?? '-' }}</li>
                        <li>TMT: {{ $tmtFormatted }}</li>
                    </ul>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mt-4">
                <ul class="m-0 ps-3">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            </div>
        @endif
    </div>
</section>
@endsection
