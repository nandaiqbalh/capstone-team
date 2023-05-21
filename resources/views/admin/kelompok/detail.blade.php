
@extends('admin.base.app')

@section('title')
    Mahasiswa
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Contoh Halaman</h5>
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
                                        <td>Nomor</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                    </tr>
                                    <tr>
                                        <td>Judul TA</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->judul_ta }}</td>
                                    </tr>
                                    <tr>
                                        <td>Topik</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->nama_topik }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->status_kelompok }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p>List Mahasiswa</p>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Nama</th>
                                        <th>NIM</th>
                                        <th width="18%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_mahasiswa->count() > 0)
                                    @foreach($rs_mahasiswa as $index => $mahasiswa)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $mahasiswa->user_name }}</td>
                                        <td>{{ $mahasiswa->nomor_induk }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/admin/mahasiswa/detail') }}/{{ $mahasiswa->user_id }}" class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                            <a href="{{ url('/admin/mahasiswa/edit') }}/{{ $mahasiswa->user_id }}" class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                            <a href="{{ url('/admin/mahasiswa/delete-process') }}/{{ $mahasiswa->user_id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $mahasiswa->user_name }} ?')"> Hapus</a>
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
                </div>
            </div>
@endsection