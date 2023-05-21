
@extends('admin.base.app')

@section('title')
    Mahasiswa
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4">Mahasiswa</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Mahasiswa</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/mahasiswa') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
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
                                        <td>NIM</td>
                                        <td>:</td>
                                        <td>{{ $mahasiswa->nomor_induk }}</td>
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
                                        <td>Alamat</td>
                                        <td>:</td>
                                        <td>{{ $mahasiswa->alamat }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
@endsection