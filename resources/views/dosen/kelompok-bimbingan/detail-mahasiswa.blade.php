@extends('tim_capstone.base.app')

@section('title')
    Mahasiswa
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Mahasiswa</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Mahasiswa</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/mahasiswa') }}" class="btn btn-secondary btn-xs float-right"><i
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
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->user_name }}</td>
                            </tr>
                            <tr>
                                <td>Usulan Judul Capstone</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->usulan_judul_capstone }}</td>
                            </tr>
                            <tr>
                                <td>NIM</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->nomor_induk }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->user_email }}</td>
                            </tr>
                            <tr>
                                <td>Angkatan</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->angkatan }}</td>
                            </tr>
                            <tr>
                                <td>IPK</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->ipk }}</td>
                            </tr>
                            <tr>
                                <td>SKS</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->sks }}</td>
                            </tr>
                            <tr>
                                <td>No Telpon</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->no_telp }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->jenis_kelamin }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                @if (
                    $mahasiswa->id_peminatan_individu1 != null &&
                        $mahasiswa->id_peminatan_individu2 != null &&
                        $mahasiswa->id_peminatan_individu3 != null &&
                        $mahasiswa->id_peminatan_individu4 != null)
                    <p>Peminatan</p>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th>Peminatan</th>
                                    <th>Prioritas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rs_peminatan as $index => $peminatan)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $peminatan->nama_peminatan }}</td>
                                        <td>{{ $peminatan->prioritas }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- upload file mhs  --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card-body">
                        <div class="card">
                            <h5 class="card-header">Upload Makalah</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <i class='bx bxs-file-doc bx-lg'></i>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control"
                                            value="{{ $mahasiswa->file_name_makalah }}" readonly>
                                        <a href="{{ url('/file/mahasiswa/makalah') }}/{{ $mahasiswa->file_name_makalah }}"
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
                            <h5 class="card-header">Upload Laporan TA</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <i class='bx bxs-file-doc bx-lg'></i>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control"
                                            value="{{ $mahasiswa->file_name_laporan_ta }}" readonly>
                                        <a href="{{ url('/file/mahasiswa/laporan-ta') }}/{{ $mahasiswa->file_name_laporan_ta }}"
                                            class="btn btn-primary float-end m-1 btn-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- upload file end  --}}
        </div>
    </div>
@endsection
