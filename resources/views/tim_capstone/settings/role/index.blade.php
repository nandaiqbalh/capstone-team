@extends('tim_capstone.base.app')

@section('title')
    Pengaturan Role
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Role</h5>
        <!-- notification -->
        @include("template.notification")

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Role</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/settings/role/search') }}" method="get" autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="role_name" value="{{ !empty($role_name) ? $role_name : '' }}" placeholder="Nama Role" minlength="3" required>
                                </div>
                                <div class="col-auto mt-1">
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action" value="search">
                                        <i class="bx bx-search-alt-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row justify-content-end mb-2">
                    <div class="col-auto ">
                        <a href="{{ url('/admin/settings/role/add') }}" class="btn btn-info btn-sm float-right"> Tambah Data</a>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Role</th>
                                <th>Deskripsi Role</th>
                                <th width="10%">Permission</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($rs_role->count() > 0)
                                @foreach($rs_role as $index => $role)
                                <tr>
                                    <td class="text-center">{{ $index + $rs_role->firstItem() }}</td>
                                    <td>{{ $role->role_name }}</td>
                                    <td>{{ $role->role_description }}</td>
                                    <td class="text-center">{{ $role->role_permission }}</td>
                                    <td class="text-center">
                                        <a href="{{ url('/admin/settings/role/edit') }}/{{ $role->role_id }}" class="btn btn-outline-warning btn-xs m-1 ">Ubah</a>
                                        @if($role->role_id != '01')
                                        <button class="btn btn-outline-danger btn-xs m-1" onclick="confirmDelete('{{ $role->role_id }}', '{{ $role->role_name }}')">Hapus</button>
                                            <script>
                                                function confirmDelete(roleId, roleName) {
                                                    Swal.fire({
                                                        title: 'Apakah Anda yakin?',
                                                        text: "Anda tidak akan dapat mengembalikan ini!",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#d33',
                                                        cancelButtonColor: '#3085d6',
                                                        confirmButtonText: 'Ya, hapus!',
                                                        cancelButtonText: 'Batal'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            window.location.href = "{{ url('/admin/settings/role/delete_process') }}/" + roleId;
                                                        }
                                                    });
                                                }
                                            </script>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="5">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto ">
                        <p>Menampilkan {{ $rs_role->count() }} dari total {{ $rs_role->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_role->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
