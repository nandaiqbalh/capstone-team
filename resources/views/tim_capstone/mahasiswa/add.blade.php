@extends('tim_capstone.base.app')

@section('title')
    Mahasiswa
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Mahasiswa</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Mahasiswa</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/mahasiswa') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/mahasiswa/add-process') }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" value="{{ old('nama') }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>NIM<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="nim" value="{{ old('nim') }}"
                                    required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary float-right">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
