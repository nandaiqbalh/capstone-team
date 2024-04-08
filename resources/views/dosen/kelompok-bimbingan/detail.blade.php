@extends('tim_capstone.base.app')

@section('title')
    Bimbingan Saya
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Bimbingan Saya</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Bimbingan Saya</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/dosen/kelompok-bimbingan') }}" class="btn btn-secondary btn-xs float-right"><i
                            class="bx bx-chevron-left"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <!-- table info -->
                <div class="table-responsive">
                    <table class="table table-borderless table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="20%"></th>
                                <th width="5%"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nomor Kelompok</td>
                                <td>:</td>
                                <td>{{ $kelompok->nomor_kelompok }}</td>
                            </tr>
                            <tr>
                                <td>Progress Kelompok</td>
                                <td>:</td>
                                <td>{{ $kelompok->status_kelompok }}</td>
                            </tr>
                            <td>Judul Capstone</td>
                            <td>:</td>
                            <td>{{ $kelompok->judul_capstone }}</td>
                            </tr>
                            <tr>
                                <td>Topik</td>
                                <td>:</td>
                                <td>{{ $kelompok->nama_topik }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th width="5%">Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Judul Tugas Akhir</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_mahasiswa->count() > 0)
                                @foreach ($rs_mahasiswa as $index => $mahasiswa)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $mahasiswa->user_name }}</td>
                                        <td>{{ $mahasiswa->nomor_induk }}</td>
                                        <td>{{ $mahasiswa->judul_ta_mhs }}</td>
                                        <td>
                                            <a href="{{ url('/dosen/kelompok-bimbingan/detail-mahasiswa') }}/{{ $mahasiswa->id_mahasiswa }}"
                                                class="btn btn-outline-warning btn-xs m-1"> Detail</a>
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card-body">
                        <div class="card">
                            <h5 class="card-header">Dokumen C100</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <i class='bx bxs-file-doc bx-lg'></i>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" value="{{ $kelompok->file_name_c100 }}"
                                            readonly>
                                        <a href="{{ url('/file/kelompok/c100') }}/{{ $kelompok->file_name_c100 }}"
                                            class="btn btn-primary float-end m-1 btn-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-body">
                        <div class="card">
                            <h5 class="card-header">Dokumen C200</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <i class='bx bxs-file-doc bx-lg'></i>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" value="{{ $kelompok->file_name_c200 }}"
                                            readonly>
                                        <a href="{{ url('/file/kelompok/c200') }}/{{ $kelompok->file_name_c200 }}"
                                            class="btn btn-primary float-end m-1 btn-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card-body">
                        <div class="card">
                            <h5 class="card-header">Dokumen C300</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <i class='bx bxs-file-doc bx-lg'></i>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" value="{{ $kelompok->file_name_c300 }}"
                                            readonly>
                                        <a href="{{ url('/file/kelompok/c300') }}/{{ $kelompok->file_name_c300 }}"
                                            class="btn btn-primary float-end m-1 btn-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-body">
                        <div class="card">
                            <h5 class="card-header">Dokumen C400</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <i class='bx bxs-file-doc bx-lg'></i>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" value="{{ $kelompok->file_name_c400 }}"
                                            readonly>
                                        <a href="{{ url('/file/kelompok/c400') }}/{{ $kelompok->file_name_c400 }}"
                                            class="btn btn-primary float-end m-1 btn-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card-body">
                        <div class="card">
                            <h5 class="card-header">Dokumen C500</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <i class='bx bxs-file-doc bx-lg'></i>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" value="{{ $kelompok->file_name_c500 }}"
                                            readonly>
                                        <a href="{{ url('/file/kelompok/c500') }}/{{ $kelompok->file_name_c500 }}"
                                            class="btn btn-primary float-end m-1 btn-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
