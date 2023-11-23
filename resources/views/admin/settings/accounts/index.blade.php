@extends('admin.base.app')

@section('title')
Pengaturan Akun User
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Akun User</h5>
    <!-- notification -->
    @include("template.notification")

    <!-- Bordered Table -->
    <div class="card">
        <h5 class="card-header">Data Akun User</h5>

        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12">
                    <form class="form-inline" action="{{ url('/admin/settings/accounts/search') }}" method="get" autocomplete="off">
                        <div class="row">
                            <div class="col-auto mt-1">
                                <input class="form-control mr-sm-2" type="search" name="user_name" value="{{ !empty($user_name) ? $user_name : '' }}" placeholder="Nama" minlength="1">
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

            <div class="row justify-content-end mb-2">
                <div class="col-auto ">
                    <a href="{{ url('/admin/settings/accounts/add') }}" class="btn btn-primary btn-xs float-right"><i class="fas fa-plus"></i> Tambah Data</a>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>ID Pengguna</th>
                            <th>Role</th>
                            <th width="10%">Status</th>
                            <th width="18%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rs_accounts->count() > 0)
                        @foreach($rs_accounts as $index => $account)
                        <tr>
                            <td class="text-center">{{ $index + $rs_accounts->firstItem() }}</td>
                            <td>{{ $account->user_name }}</td>
                            <td>{{ $account->nomor_induk }}</td>
                            <td>
                                {{ $account->role_name }}

                            </td>
                            <td class="text-center">
                                @if($account->user_active == '1')
                                <span class="text-primary">Aktif</span>
                                @else
                                <span class="text-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                @if($role_id == '01')
                                @if(Auth::user()->user_id != $account->user_id)
                                <a href="#" data-id="{{ Crypt::encryptString($account->user_id) }}" data-nomor_induk="{{ Crypt::encryptString($account->nomor_induk) }}" class="btn btn-outline-secondary btn-xs m-1 btn-take-over-login"> Login</a>
                                @endif
                                @endif
                                <a href="{{ url('/admin/settings/accounts/edit_password') }}/{{ $account->user_id }}" class="btn btn-outline-success btn-xs m-1"> Password</a>
                                <a href="{{ url('/admin/settings/accounts/edit') }}/{{ $account->user_id }}" class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                <a href="{{ url('/admin/settings/accounts/delete_process') }}/{{ $account->user_id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus Akun {{ $account->user_name }} ?')"> Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="7">Tidak ada data.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- pagination -->
            <div class="row mt-3 justify-content-between">
                <div class="col-auto">
                    <p>Menampilkan {{ $rs_accounts->count() }} dari total {{ $rs_accounts->total() }} data.</p>
                </div>
                <div class="col-auto ">
                    {{ $rs_accounts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Loading-->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0 mt-2" id="text-loading">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(".btn-take-over-login").on("click", function(e) {
        var id = $(this).data('id');
        var nomor_induk = $(this).data('nomor_induk');
        var url = "{{ url('admin/settings/take-over-login?') }}id=" + id + "&nomor_induk=" + nomor_induk;

        // confirm
        if (confirm("Yakin ingin berganti akun?")) {
            // show loading
            $("#loadingModal").modal('show');

            setTimeout(function() {
                $("#text-loading").html('Melakukan login...');
            }, 3000);

            setTimeout(function() {
                $("#text-loading").html('Mengambil alih akun...');
            }, 6000);

            // take over login
            window.open(url, "_self");
        } else {
            // nothing
        }

    });
</script>
@endsection
