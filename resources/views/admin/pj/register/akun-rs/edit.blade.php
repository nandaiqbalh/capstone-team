@extends('admin.base.app')

@section('title')
Akun Bamasama
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Registrasi /</span> Akun Bamasama</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Akun Bamasama</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/pj/register/akun') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/pj/register/akun/edit-process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <input type="hidden" name="user_id" value="{{ $branch_account->user_id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="user_name" value="{{ $branch_account->user_name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>ID Pengguna<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nik" value="{{ old('nik', $branch_account->nik) }}" minlength="6" maxlength="11" placeholder="NIK" pattern="[0-9]+" required>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="user_email" value="{{ $branch_account->user_email }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>No. Telepon</label>
                                        <input type="number" class="form-control" name="no_telp" value="{{ old('no_telp', $branch_account->no_telp) }}">
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