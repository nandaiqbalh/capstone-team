@extends('admin.base.app')

@section('title')
Bimbingan Saya
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"> Bimbingan Saya</h5>
    <!-- notification -->
    @include("template.notification")

    <!-- Bordered Table -->
    <div class="card">
        <h5 class="card-header">Data Bimbingan Saya</h5>

        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12">
                    <form class="form-inline" action="{{ url('/admin/kelompok/search') }}" method="get" autocomplete="off">
                        <div class="row">
                            <div class="col-auto mt-1">
                                <input class="form-control mr-sm-2" type="search" name="nama" value="{{ !empty($nama) ? $nama : '' }}" placeholder="Nama Role" minlength="3" required>
                            </div>
                            <div class="col-auto mt-1">
                                <button class="btn btn-outline-secondary ml-1" type="submit" name="action" value="search">
                                    <i class="bx bx-search-alt-2"></i>
                                </button>
                                <button class="btn btn-outline-secondary ml-1" type="submit" name="action" value="reset">
                                    <i class="bx bx-reset"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            {{-- <div class="row justify-content-end mb-2">
                <div class="col-auto ">
                    <a href="{{ url('/admin/kelompok/add') }}" class="btn btn-primary btn-xs float-right"><i class="fas fa-plus"></i> Tambah Data</a>
                </div>
            </div> --}}

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nomor Kelompok</th>
                            <th>Judul TA</th>
                            <th>Topik</th>
                            <th>Dosen</th>
                            <th width="18%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rs_bimbingan_saya->count() > 0)
                        @foreach($rs_bimbingan_saya as $index => $kelompok)
                        <tr>
                            <td class="text-center">{{ $index + $rs_bimbingan_saya->firstItem() }}.</td>
                            <td>{{ $kelompok->nomor_kelompok }}</td>
                            <td>{{ $kelompok->judul_ta }}</td>
                            <td>{{ $kelompok->topik_name }}</td>
                            <td>{{ $kelompok->dosen_name }}</td>
                            <td class="text-center">
                                <a href="{{ url('/dosen/bimbingan-saya/detail') }}/{{ $kelompok->id }}" class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                <a href="{{ url('/dosen/bimbingan-saya/edit') }}/{{ $kelompok->id }}" class="btn btn-outline-warning btn-xs m-1 "onclick="return confirm('Apakah anda ingin menerima {{ $kelompok->nomor_kelompok }} ?')">  Terima</a>
                                <a href="{{ url('/dosen/bimbingan-saya/delete-process') }}/{{ $kelompok->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menolak {{ $kelompok->nomor_kelompok }} ?')"> Tolak</a>
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
                    <p>Menampilkan {{ $rs_bimbingan_saya->count() }} dari total {{ $rs_bimbingan_saya->total() }} data.</p>
                </div>
                <div class="col-auto ">
                    {{ $rs_bimbingan_saya->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection