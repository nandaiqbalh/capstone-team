
@extends('admin.base.app')

@section('title')
    Bimbingan Saya
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Bimbingan Saya</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Bimbingan Saya</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/dosen/bimbingan-saya') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_mahasiswa->count() > 0)
                                    @foreach($rs_mahasiswa as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $kelompok->user_name }}</td>
                                        <td>{{ $kelompok->nomor_induk }}</td>
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
                </div>
            </div>
@endsection
