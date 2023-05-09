@extends('admin.base.app')

@section('title')
    Cabang
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4">Cabang</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pendaftaran Akun Admin Cabang <strong>{{$branch->name}}</strong> </h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/manajer/cabang') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/manajer/cabang/akun_add_process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <input type="hidden" name="branch_id" value="{{$branch->id}}" required>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama Lengkap<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="user_name" value="{{ old('user_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>ID Pengguna<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="id_pengguna" value="{{ old('id_pengguna') }}" minlength="6" maxlength="15" pattern="[0-9]+" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="user_email" value="{{ old('user_email') }}" required>
                                        <p class="mt-2">* kata sandi akan otomatis dikirimkan ke email yang didaftarkan.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nomor Telepon<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="no_telp" value="{{ old('no_telp') }}" required>
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