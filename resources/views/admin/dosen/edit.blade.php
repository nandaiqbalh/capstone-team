
@extends('admin.base.app')

@section('title')
    Dosen
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span>Dosen</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Dosen</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/dosen') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/dosen/edit-process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <input type="hidden" name="user_id" value="{{ $dosen->user_id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Nama<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" value="{{ $dosen->user_name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>NIP<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="nip" value="{{ $dosen->nomor_induk }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Role <span class="text-danger">*</span></label>
                                        <select class="form-select" name="role" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            <option value="05" @if( $dosen->role_id == '05' ) selected @endif>Tim Capstone</option>
                                            <option value="04" @if( $dosen->role_id == '04' ) selected @endif>Dosen</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Alamat<span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alamat" placeholder="Tulis Alamat" id="floatingTextarea" required>{{ $dosen->alamat }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <br>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
@endsection