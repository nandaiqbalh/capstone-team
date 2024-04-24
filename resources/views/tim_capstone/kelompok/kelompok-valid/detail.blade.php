@extends('tim_capstone.base.app')

@section('title')
    Kelompok
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">Detail Kelompok</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Kelompok</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/kelompok-valid') }}" class="btn btn-danger btn-sm float-right"><i
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
                                <td>Status Sidang Proposal</td>
                                <td>:</td>
                                @if ($kelompok->status_sidang_proposal == null)
                                    <td>-</td>
                                @else
                                    <td style="color: {{ $kelompok->status_sidang_color }}">
                                        {{ $kelompok->status_sidang_proposal }}</td>
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
                                <th>Judul Tugas Akhir</th>
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

                <br>
                <h6 class="mb-0">Status Persetujuan Dokumen</h6>
                <br>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Dokumen</th>
                                <th>Status Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1.</td>
                                <td>Dokumen C100</td>
                                <td style="color: {{ $kelompok->status_c100_color }}">
                                    @if ($kelompok->file_status_c100 !== null)
                                        {{ $kelompok->file_status_c100 }}
                                    @else
                                        Belum Mengunggah!
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2.</td>
                                <td>Dokumen C200</td>
                                <td style="color: {{ $kelompok->status_c200_color }}">
                                    @if ($kelompok->file_status_c200 !== null)
                                        {{ $kelompok->file_status_c200 }}
                                    @else
                                        Belum Mengunggah!
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">3.</td>
                                <td>Dokumen C300</td>
                                <td style="color: {{ $kelompok->status_c300_color }}">
                                    @if ($kelompok->file_status_c300 !== null)
                                        {{ $kelompok->file_status_c300 }}
                                    @else
                                        Belum Mengunggah!
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">4.</td>
                                <td>Dokumen C400</td>
                                <td style="color: {{ $kelompok->status_c400_color }}">
                                    @if ($kelompok->file_status_c400 !== null)
                                        {{ $kelompok->file_status_c400 }}
                                    @else
                                        Belum Mengunggah!
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">5.</td>
                                <td>Dokumen C500</td>
                                <td style="color: {{ $kelompok->status_c500_color }}">
                                    @if ($kelompok->file_status_c500 !== null)
                                        {{ $kelompok->file_status_c500 }}
                                    @else
                                        Belum Mengunggah!
                                    @endif
                                </td>
                            </tr>
                            <!-- Tambahkan baris tambahan sesuai dengan jumlah dokumen yang ingin ditampilkan -->
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
                                        <input type="text" class="form-control"
                                            value="{{ $kelompok->file_name_c400 }}" readonly>
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
