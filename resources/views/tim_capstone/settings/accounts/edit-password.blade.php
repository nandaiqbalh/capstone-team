@extends('tim_capstone.base.app')

@section('title')
    Akun User
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Akun User</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Password Akun User</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/settings/accounts') }}" class="btn btn-danger btn-sm float-right"><i class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/settings/accounts/edit_password_process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <input type="hidden" name="user_id" value="{{ $account->user_id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Password Baru<span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="new_password" required>
                                        <small class="form-text text-muted">Minimal 8 karakter, minimal mengandung angka dan huruf kapital.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Ulangi Password Baru<span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="repeat_new_password" required>
                                        <small class="form-text text-muted">Minimal 8 karakter, minimal mengandung angka dan huruf kapital.</small>
                                    </div>
                                </div>
                            </div>

                            <br>
                        </div>
                        <div class="card-footer float-end">
                            <button type="submit" class="btn btn-primary btn">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
@endsection
