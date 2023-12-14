@extends('admin.base.app')

@section('title')
    Pengaturan Menu
@endsection

@section('content')

            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Menu</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">Data Menu</h5>

                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/settings/menu/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-auto mt-1">
                                            <input class="form-control mr-sm-2" type="search" name="menu_name" value="{{ !empty($menu_name) ? $menu_name : '' }}" placeholder="Nama Menu" minlength="3" required>
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
                                <a href="{{ url('/admin/settings/menu/add') }}" class="btn btn-primary btn-xs float-right"><i class="fas fa-plus"></i> Tambah Data</a>
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th width="5%">Icon</th>
                                        <th>Nama menu</th>
                                        <th>Deskripsi menu</th>
                                        <th >Aktif</th>
                                        <th >Tampil</th>
                                        <th width="25%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_menu->count() > 0)
                                        @foreach($rs_menu as $index => $menu)
                                        <tr>
                                            <td class="text-center"><i class="mdi {{$menu->menu_icon}}"></i></td>
                                            <td>{{ !empty($menu->parent_menu_id) ? '-- -- '.$menu->menu_name : $menu->menu_name }}</td>
                                            <td>{{ $menu->menu_description }}</td>
                                            <td class="text-center">{{ $menu->menu_active ? 'Ya' : 'Tidak' }} </td>
                                            <td class="text-center">{{ $menu->menu_display ? 'Ya' : 'Tidak' }} </td>
                                            <td class="text-center">
                                                <a href="{{ url('/admin/settings/menu/role_menu') }}/{{ $menu->id }}" class="btn btn-outline-success btn-xs m-1"> Role</a>
                                                <a href="{{ url('/admin/settings/menu/edit') }}/{{ $menu->id }}" class="btn btn-outline-warning btn-xs m-1"> Ubah</a>
                                                <a href="{{ url('/admin/settings/menu/delete_process') }}/{{ $menu->id }}" class="btn btn-outline-danger btn-xs m-1" onclick="return confirm('Apakah anda ingin menghapus menu {{ $menu->menu_name }} ?')"> Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td  colspan="6">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto mr-auto">
                                <p>Menampilkan {{ $rs_menu->count() }} dari total {{ $rs_menu->total() }} data.</p>
                            </div>
                            <div class="col-auto">
                                {{ $rs_menu->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection
