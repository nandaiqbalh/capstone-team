@extends('tim_capstone.base.app')

@section('title')
    Menu
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Menu</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Menu</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/settings/menu') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <form action="{{ url('/admin/settings/menu/add_process') }}" method="post" autocomplete="off">
                <div class="card-body">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Parent Menu <span class="text-danger">*</span></label>
                                <select class="form-select select-2" name="parent_menu_id" required>
                                    <option value="parent" selected>Parent Menu</option>
                                    @foreach ($rs_menu as $menu)
                                        <option value="{{ $menu->id }}"
                                            @if (old('parent_menu_id') == $menu->id) selected @endif>
                                            {{ !empty($menu->parent_menu_id) ? '-- -- ' . $menu->menu_name : $menu->menu_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="menu_name" value="{{ old('menu_name') }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Deskripsi Menu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="menu_description"
                                    value="{{ old('menu_description') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Url Menu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="menu_url" value="{{ old('menu_url') }}"
                                    placeholder="admin/.." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Urutan Menu <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="menu_sort" value="{{ old('menu_sort') }}"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Menu Group <span class="text-danger">*</span></label>
                                <select class="form-select" name="menu_group" required>
                                    <option value="utama" @if (old('menu_group') == 'utama') selected @endif>Utama</option>
                                    <option value="system" @if (old('menu_group') == 'system') selected @endif>System</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Icon Menu</label>
                                <input type="text" class="form-control" name="menu_icon" value="{{ old('menu_icon') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Menu Aktif? <span class="text-danger">*</span></label>
                                <select class="form-select" name="menu_active" required>
                                    <option value="1" @if (old('menu_active') == '1') selected @endif>Ya</option>
                                    <option value="0" @if (old('menu_active') == '0') selected @endif>Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tampilkan Menu? <span class="text-danger">*</span></label>
                                <select class="form-select" name="menu_display" required>
                                    <option value="1" @if (old('menu_display') == '1') selected @endif>Ya</option>
                                    <option value="0" @if (old('menu_display') == '0') selected @endif>Tidak</option>
                                </select>
                            </div>
                        </div>
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
