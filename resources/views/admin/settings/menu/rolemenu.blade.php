@extends('admin.base.app')

@section('title')
    Menu
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Menu</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Atur Role Menu</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/settings/menu') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/settings/menu/role_menu_process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}

                            <input type="hidden" name="menu_id" value="{{ $menu->menu_id }}">
                            <!-- table -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr class="text-center">
                                            <th width="5%">No</th>
                                            <th>Nama Role</th>
                                            <th width="10%">Permission</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($rs_role->count() > 0)
                                            @foreach($rs_role as $index => $role)
                                            <tr>
                                                <td>{{ $index+1 }}</td>
                                                <td>{{ $role->role_name }}</td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="{{ $role->role_id }}" value="{{ $role->role_id }}" name="role_id[]" @if(in_array($role->role_id, array_column($rs_role_menu,'role_id'))) checked @endif @if($role->role_id == '01') disabled @endif>
                                                        <label class="custom-control-label" for="{{ $role->role_id }}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td  colspan="3">Tidak ada data.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            
                            <br>
                        </div>
                        <div class="card-footer float-end">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
@endsection