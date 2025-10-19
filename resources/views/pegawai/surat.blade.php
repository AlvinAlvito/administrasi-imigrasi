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
                    <i class="uil uil-file-plus-alt"></i>
                    <span class="text">Surat Perjalanan Dinas Saya</span>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger mt-2">
                        <ul class="m-0 ps-3">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row justify-content-end mb-3">
                    <div class="col-lg-3 text-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="uil uil-plus"></i> Ajukan Surat
                        </button>
                    </div>
                </div>

                <table id="datatable" class="table table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Surat</th>
                            <th>Pimpinan</th>
                            <th>Tujuan</th>
                            <th>Berangkat</th>
                            <th>Kembali</th>
                            <th>Status</th>
                            <th>Total Biaya</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($surat as $s)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $s->no_surat }}</td>
                                <td>{{ $s->pimpinan->name ?? '-' }}</td>
                                <td>{{ $s->tujuan }}</td>
                                <td>{{ \Carbon\Carbon::parse($s->tanggal_berangkat)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($s->tanggal_kembali)->format('d/m/Y') }}</td>
                                <td>
                                    @php $badge = ['menunggu'=>'secondary','diterima'=>'success','ditolak'=>'danger'][$s->status] ?? 'secondary'; @endphp
                                    <span class="badge bg-{{ $badge }}">{{ ucfirst($s->status) }}</span>
                                </td>
                                <td>
                                    @if ($s->rincianBiaya)
                                        Rp {{ number_format($s->rincianBiaya->jumlah_total, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    {{-- Edit & Hapus: hanya jika masih menunggu --}}
                                    @if ($s->status === 'menunggu')
                                        <button class="btn btn-link text-primary p-0 m-0" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $s->id }}">
                                            <i class="uil uil-edit"></i>
                                        </button>
                                        <form action="{{ route('pegawai.surat.destroy', $s->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus pengajuan ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-link text-danger p-0 m-0">
                                                <i class="uil uil-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Download PDF: hanya tampil jika status "diterima" --}}
                                    @if ($s->status === 'diterima')
                                        <a href="{{ route('pegawai.surat.pdf', $s->id) }}" class="btn btn-link p-0 m-0"
                                            title="Cetak PDF">
                                            <i class="uil uil-file-download"></i>
                                        </a>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('pegawai.surat.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajukan Surat Perjalanan Dinas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Pimpinan Penanggung Jawab</label>
                                <select name="pimpinan_id" class="form-control" required>
                                    <option value="">-- Pilih Pimpinan --</option>
                                    @foreach ($listPimpinan as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->jabatan }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Tanggal Berangkat</label>
                                <input type="date" name="tanggal_berangkat" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label>Tujuan</label>
                                <input type="text" name="tujuan" class="form-control" placeholder="Kota/Instansi Tujuan"
                                    required>
                            </div>
                            <div class="col-12">
                                <label>Maksud Perjalanan</label>
                                <textarea name="maksud_perjalanan" class="form-control" rows="2" placeholder="Tuliskan maksud perjalanan"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Alat Transportasi</label>
                                <input type="text" name="alat_transportasi" class="form-control"
                                    placeholder="Mobil Dinas/Kereta Api/dll">
                            </div>

                            <div class="col-md-3">
                                <label>Uang Harian</label>
                                <input type="number" step="0.01" name="uang_harian" class="form-control" value="0">
                            </div>
                            <div class="col-md-3">
                                <label>Transportasi</label>
                                <input type="number" step="0.01" name="transportasi" class="form-control"
                                    value="0">
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

    {{-- Modal Edit --}}
    @foreach ($surat as $s)
        <div class="modal fade" id="modalEdit{{ $s->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('pegawai.surat.update', $s->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Pengajuan ({{ $s->no_surat }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Pimpinan Penanggung Jawab</label>
                                    <select name="pimpinan_id" class="form-control" required>
                                        @foreach ($listPimpinan as $p)
                                            <option value="{{ $p->id }}" @selected($s->pimpinan_id == $p->id)>
                                                {{ $p->name }} ({{ $p->jabatan }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Tanggal Berangkat</label>
                                    <input type="date" name="tanggal_berangkat" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($s->tanggal_berangkat)->format('Y-m-d') }}"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label>Tanggal Kembali</label>
                                    <input type="date" name="tanggal_kembali" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($s->tanggal_kembali)->format('Y-m-d') }}"
                                        required>
                                </div>

                                <div class="col-12">
                                    <label>Tujuan</label>
                                    <input type="text" name="tujuan" class="form-control"
                                        value="{{ $s->tujuan }}" required>
                                </div>
                                <div class="col-12">
                                    <label>Maksud Perjalanan</label>
                                    <textarea name="maksud_perjalanan" class="form-control" rows="2">{{ $s->maksud_perjalanan }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>Alat Transportasi</label>
                                    <input type="text" name="alat_transportasi" class="form-control"
                                        value="{{ $s->alat_transportasi }}">
                                </div>

                                <div class="col-md-3">
                                    <label>Uang Harian</label>
                                    <input type="number" step="0.01" name="uang_harian" class="form-control"
                                        value="{{ $s->rincianBiaya->uang_harian ?? 0 }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Transportasi</label>
                                    <input type="number" step="0.01" name="transportasi" class="form-control"
                                        value="{{ $s->rincianBiaya->transportasi ?? 0 }}">
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

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(function() {
            $('#datatable').DataTable();
        });
    </script>
@endsection
