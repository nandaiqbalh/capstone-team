@extends('tim_capstone.base.app')

@section('title')
    Rest Api
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Rest API</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Rest API</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/settings/rest-api/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="user_name"
                                        value="{{ !empty($user_name) ? $user_name : '' }}" placeholder="Nama" minlength="1"
                                        required>
                                </div>
                                <div class="col-auto mt-1">
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="search">
                                        <i class="bx bx-search-alt-2"></i>
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row justify-content-end mb-2">
                    <div class="col-auto ">

                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Rumah Sakit</th>
                                <th width="15%">Role</th>
                                <th width="15%">Jumlah Token</th>
                                <th width="6%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_user_api_active->count() > 0)
                                @foreach ($rs_user_api_active as $index => $user_api_active)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_user_api_active->firstItem() }}</td>
                                        <td>{{ $user_api_active->user_name }}</td>
                                        <td>{{ $user_api_active->branch_name ? $user_api_active->branch_name : '-' }}</td>
                                        <td>{{ $user_api_active->role_name }}</td>
                                        <td class="text-center">{{ $user_api_active->jumlah_token }}</td>
                                        <td>
                                            <a href="{{ url('/admin/settings/rest-api/delete_process') }}/{{ $user_api_active->user_id }}"
                                                class="btn btn-outline-danger btn-xs m-1"
                                                onclick="return confirm('Yakin ingin menghapus koneksi API untuk user {{ $user_api_active->user_name }} ?')"><i
                                                    class="fas fa-trash-alt"></i> Hapus</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto ">
                        <p>Menampilkan {{ $rs_user_api_active->count() }} dari total {{ $rs_user_api_active->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_user_api_active->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
