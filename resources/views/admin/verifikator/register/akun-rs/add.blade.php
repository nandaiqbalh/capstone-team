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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tambah Akun User</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/checker/register/akun-rs') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/checker/register/akun-rs/add-process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama Lengkap<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="user_name" value="{{ old('user_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>NIK<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nik" value="{{ old('nik') }}" minlength="16" maxlength="16" placeholder="NIK" pattern="[0-9]+" required>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="user_email" value="{{ old('user_email') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Role Akun <span class="text-danger">*</span></label>
                                        <select class="form-select" name="role_id" required>
                                            <option value="" selected disabled>Pilih</option>
                                            <option value="03" @if( old('role_id') == '03' ) selected @endif>Verifikator 1 (Wakil Direktur)</option>
                                            <option value="04" @if( old('role_id') == '04' ) selected @endif>Verifikator 2 (Direktur)</option>
    
                                        </select>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                            

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>No. Telepon</label>
                                        <input type="number" class="form-control" name="no_telp" value="{{ old('no_telp') }}" >
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