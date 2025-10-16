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
                <i class="uil uil-estate"></i>
                <span class="text">Dashboard Admin</span>
            </div>

            <div class="row g-3">
                <div class="col-lg-2 col-6">
                    <div class="card p-3">
                        <div class="fw-bold">Pegawai</div>
                        <div class="fs-4">{{ $totalPegawai }}</div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="card p-3">
                        <div class="fw-bold">Pimpinan</div>
                        <div class="fs-4">{{ $totalPimpinan }}</div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="card p-3">
                        <div class="fw-bold">Surat</div>
                        <div class="fs-4">{{ $totalSurat }}</div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="card p-3">
                        <div class="fw-bold">Menunggu</div>
                        <div class="fs-4">{{ $menunggu }}</div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="card p-3">
                        <div class="fw-bold">Diterima</div>
                        <div class="fs-4">{{ $diterima }}</div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="card p-3">
                        <div class="fw-bold">Ditolak</div>
                        <div class="fs-4">{{ $ditolak }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
