
@extends('tim_capstone.base.app')

@section('title')
    Peminatan
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Peminatan</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tambah Peminatan</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/peminatan') }}" class="btn btn-danger btn-sm float-right"><i class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/peminatan/add-process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Nama Peminatan<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_peminatan" value="{{ old('nama_peminatan') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer float-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
@endsection