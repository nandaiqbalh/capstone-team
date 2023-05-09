@extends('admin.base.app')

@section('title')
    Akun Rumah Sakit
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Register /</span> Akun Rumah Sakit</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">Daftar Akun Rumah Sakit</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/checker/register/akun-rs/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-auto mt-1">
                                            <input class="form-control mr-sm-2" type="search" name="search" value="{{ !empty($search) ? $search : '' }}" placeholder="Cari ..." minlength="1" required>
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
                        <div class="row justify-content-end mb-2">
                            <div class="col-auto ">
                                <a href="{{ url('/admin/checker/register/akun-rs/add') }}" class="btn btn-primary btn-xs float-right"><i class="bx bx-plus"></i> Tambah</a>
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Posisi</th>
                                        <th>Telepon</th>
                                        <th>Email</th>
                                        <th width="18%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @if($rs_branch_account->count() > 0)
                                            @foreach($rs_branch_account as $index => $branch_account)
                                            <tr>
                                                <td class="text-center">{{ $index + $rs_branch_account->firstItem() }}.</td>
                                                <td>{{ $branch_account->user_name }}</td>
                                                <td>{{ $branch_account->nik }}</td>
                                                <td>{{ $branch_account->position }}</td>
                                                <td>{{ $branch_account->no_telp }}</td>
                                                <td>{{ $branch_account->user_email }}</td>
                                                <td class="text-center">
                                                    <a href="{{ url('/admin/checker/register/akun-rs/edit') }}/{{ $branch_account->user_id }}" class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                                    <a href="{{ url('/admin/checker/register/akun-rs/delete-process') }}/{{ $branch_account->user_id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $branch_account->user_name }} ?')"> Hapus</a>
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
                                <p>Menampilkan {{ $rs_branch_account->count() }} dari total {{ $rs_branch_account->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_branch_account->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
@endsection