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
                <i class="uil uil-user-check"></i>
                <span class="text">Akun Pimpinan</span>
            </div>

            @if (session('success'))
                <div class="alert alert-success mt-2">{{ session('success') }}</div>
            @endif

            <div class="row justify-content-end mb-3">
                <div class="col-lg-3 text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="uil uil-plus"></i> Tambah Pimpinan
                    </button>
                </div>
            </div>

            <table id="datatable" class="table table-hover table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>JK</th>
                        <th>Jabatan</th>
                        <th>Eselon</th>
                        <th>Pangkat/Gol</th>
                        <th>TMT</th>
                        <th>Pendidikan</th>
                        <th>Diklat Teknis</th>
                        <th>Tanda Tangan</th>
                        <th style="width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pimpinan as $p)
                        @php $prof = $p->pimpinanProfile; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->nip }}</td>
                            <td>{{ $prof->jenis_kelamin ?? '-' }}</td>
                            <td>{{ $p->jabatan }}</td>
                            <td>{{ $prof->eselon ?? '-' }}</td>
                            <td>{{ $prof->pangkat_gol ?? '-' }}</td>
                            <td>{{ optional($prof->tmt)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $prof->pendidikan ?? '-' }}</td>
                            <td>{{ $prof->diklat_teknis ?? '-' }}</td>
                            <td>
                                @if ($prof && $prof->tanda_tangan)
                                    <img src="{{ asset('storage/'.$prof->tanda_tangan) }}" alt="TTD" width="80">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-link text-primary p-0 m-0" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $p->id }}">
                                    <i class="uil uil-edit"></i>
                                </button>
                                <form action="{{ route('admin.pimpinan.destroy', $p->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus akun ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 m-0">
                                        <i class="uil uil-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="text-center">Belum ada akun pimpinan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Modal Edit --}}
@foreach ($pimpinan as $p)
@php $prof = $p->pimpinanProfile; @endphp
<div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.pimpinan.update', $p->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Akun Pimpinan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ $p->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $p->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label>NIP</label>
                            <input type="text" name="nip" class="form-control" value="{{ $p->nip }}">
                        </div>
                        <div class="col-md-6">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ $p->jabatan }}">
                        </div>

                        <div class="col-md-3">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                                <option value="">-</option>
                                <option value="L" @selected(($prof->jenis_kelamin ?? '')==='L')>L</option>
                                <option value="P" @selected(($prof->jenis_kelamin ?? '')==='P')>P</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Eselon</label>
                            <input type="text" name="eselon" class="form-control" value="{{ $prof->eselon ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label>Pangkat/Gol</label>
                            <input type="text" name="pangkat_gol" class="form-control" value="{{ $prof->pangkat_gol ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label>TMT</label>
                            <input type="date" name="tmt" class="form-control" value="{{ optional($prof->tmt)->format('Y-m-d') }}">
                        </div>

                        <div class="col-md-6">
                            <label>Pendidikan</label>
                            <input type="text" name="pendidikan" class="form-control" value="{{ $prof->pendidikan ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label>Diklat Teknis</label>
                            <input type="text" name="diklat_teknis" class="form-control" value="{{ $prof->diklat_teknis ?? '' }}">
                        </div>

                        <div class="col-md-6">
                            <label>Tanda Tangan (jpg/png)</label>
                            <input type="file" name="tanda_tangan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Password (opsional)</label>
                            <input type="text" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ganti">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.pimpinan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Akun Pimpinan (Prefill)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Email hidden agar validasi lolos --}}
                        <input type="hidden" name="email" value="barandaru.widyarto@test.local">

                        <div class="col-md-6">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control"
                                   value="BARANDARU WIDYARTO" required>
                        </div>
                        <div class="col-md-6">
                            <label>NIP</label>
                            <input type="text" name="nip" class="form-control" value="">
                        </div>

                        <div class="col-md-6">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" class="form-control"
                                   value="KEPALA KANTOR IMIGRASI">
                        </div>
                        <div class="col-md-3">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                                <option value="L" selected>L</option>
                                <option value="P">P</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Eselon</label>
                            <input type="text" name="eselon" class="form-control" value="III.b">
                        </div>

                        <div class="col-md-6">
                            <label>Pangkat/Gol</label>
                            <input type="text" name="pangkat_gol" class="form-control"
                                   value="PENATA TK. I (III/d)">
                        </div>
                        <div class="col-md-6">
                            <label>TMT</label>
                            <input type="date" name="tmt" class="form-control" value="2024-04-01">
                        </div>

                        <div class="col-md-6">
                            <label>Pendidikan</label>
                            <input type="text" name="pendidikan" class="form-control"
                                   value="S2 MAGISTER ILMU HUKUM">
                        </div>
                        <div class="col-md-6">
                            <label>Diklat Teknis</label>
                            <input type="text" name="diklat_teknis" class="form-control"
                                   value="AKADEMI IMIGRASI">
                        </div>

                        <div class="col-md-6">
                            <label>Tanda Tangan (jpg/png)</label>
                            <input type="file" name="tanda_tangan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Password (default 12345678 jika kosong)</label>
                            <input type="text" name="password" class="form-control" placeholder="Kosongkan jika default">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>


{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>$(function(){ $('#datatable').DataTable(); });</script>
@endsection
