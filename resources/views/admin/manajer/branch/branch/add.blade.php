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
                        <h5 class="mb-0">Pendaftaran Cabang</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/manajer/cabang') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/manajer/cabang/add_process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Cabang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Alamat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" value="{{ old('address') }}" required>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nomor Telepon<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="no_telp" value="{{ old('no_telp')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nomor Rekening<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="no_rekening" value="{{ old('no_rekening')}}" required>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama Bank<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="bank_rekening" value="{{ old('bank_rekening')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama Pemilik Rekening<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="an_rekening" value="{{ old('an_rekening')}}" required>
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