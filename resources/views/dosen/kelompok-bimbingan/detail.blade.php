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
                    <a href="{{ url('/dosen/kelompok-bimbingan') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left"></i> Kembali</a>
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
                                @if ($kelompok->nomor_kelompok == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $kelompok->nomor_kelompok }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Siklus Pendaftaran</td>
                                <td>:</td>
                                @if ($kelompok->nama_siklus == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $kelompok->nama_siklus }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Status Kelompok</td>
                                <td>:</td>
                                @if ($kelompok->status_kelompok == null)
                                    <td>-</td>
                                @else
                                    <td style="color: {{ $kelompok->status_kelompok_color }}">
                                        {{ $kelompok->status_kelompok }}</td>
                                @endif
                            </tr>

                            <tr>
                                <td>Judul Capstone</td>
                                <td>:</td>
                                @if ($kelompok->judul_capstone == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $kelompok->judul_capstone }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Topik</td>
                                <td>:</td>
                                @if ($kelompok->nama_topik == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $kelompok->nama_topik }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>

                </div>
                <hr>
                <h6>List Mahasiswa</h6>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Status Mahasiswa</th>
                                <th>Judul Tugas Akhir</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_mahasiswa->count() > 0)
                                @foreach ($rs_mahasiswa as $index => $mahasiswa)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $mahasiswa->user_name }}</td>
                                        <td>{{ $mahasiswa->nomor_induk }}</td>
                                        <td style="color: {{ $mahasiswa->status_mahasiswa_color }}">
                                            {{ $mahasiswa->status_individu }}</td>
                                        <td>{{ $mahasiswa->judul_ta_mhs ?: '-' }}</td>
                                        <td class="text-center">

                                            <a href="{{ url('/dosen/kelompok-bimbingan/detail-mahasiswa') }}/{{ $mahasiswa->id_mahasiswa }}"
                                                class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="6">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <br>

                <h6 class="mb-0">List Dosen Pembimbing</h6>
                <br>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Dosbing</th>
                                <th>NIP/NIDN</th>
                                <th>Posisi</th>
                                <th>Status Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_dosbing->count() > 0)
                                @foreach ($rs_dosbing as $index => $dosbing)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $dosbing->user_name }}</td>
                                        <td>{{ $dosbing->nomor_induk }}</td>
                                        <td>{{ $dosbing->jenis_dosen }}</td>
                                        @if ($dosbing->jenis_dosen == 'Pembimbing 1')
                                            <td style="color: {{ $kelompok->status_dosbing1_color }}">
                                                {{ $dosbing->status_dosen }}</td>
                                        @else
                                            <td style="color: {{ $kelompok->status_dosbing2_color }}">
                                                {{ $dosbing->status_dosen }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="5">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
            {{-- c series  --}}

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
                                        <input type="text" class="form-control"
                                            value="{{ $kelompok->file_name_c500 }}" readonly>
                                        <a href="{{ url('/file/kelompok/c500') }}/{{ $kelompok->file_name_c500 }}"
                                            class="btn btn-primary float-end m-1 btn-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{-- c series end  --}}
        </div>
    </div>
@endsection
