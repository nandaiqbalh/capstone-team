
@extends('admin.base.app')

@section('title')
    Dosen
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4">Tambah Data Dosen</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tambah Dosen</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/settings/contoh-halaman') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/dosen/add-process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Nama<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>NIP<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="nip" value="{{ old('nip') }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Role <span class="text-danger">*</span></label>
                                        <select class="form-select" name="role_id" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            <option value="02" @if( old('role_id') == '02' ) selected @endif>Tim Capstone</option>
                                            <option value="04" @if( old('role_id') == '04' ) selected @endif>Dosen</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Alamat<span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alamat" placeholder="Tulis Alamat" id="floatingTextarea" required></textarea>
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