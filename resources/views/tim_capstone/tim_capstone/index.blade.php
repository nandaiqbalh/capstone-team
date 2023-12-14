@extends('tim_capstone.base.app')

@section('title')
Mahasiswa
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Mahasiswa</h5>
    <!-- notification -->
    @include("template.notification")

    <!-- Bordered Table -->
    <div class="card">
        <h5 class="card-header">Data Mahasiswa</h5>

        <div class="card-body">

            <br>
            <div class="row justify-content-end mb-2">
                <div class="col-auto ">
                    <a href="{{ url('/admin/mahasiswa/add') }}" class="btn btn-primary btn-xs float-right"><i class="fas fa-plus"></i> Tambah Data</a>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th width="18%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rs_mahasiswa->count() > 0)
                        @foreach($rs_mahasiswa as $index => $mahasiswa)
                        <tr>
                            <td class="text-center">{{ $index + $rs_mahasiswa->firstItem() }}.</td>
                            <td>{{ $mahasiswa->user_name }}</td>
                            <td>{{ $mahasiswa->role_name }}</td>
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
            <!-- pagination -->
            <div class="row mt-3 justify-content-between">
                <div class="col-auto mr-auto">
                    <p>Menampilkan {{ $rs_mahasiswa->count() }} dari total {{ $rs_mahasiswa->total() }} data.</p>
                </div>
                <div class="col-auto ">
                    {{ $rs_mahasiswa->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
