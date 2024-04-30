@extends('tim_capstone.base.app')

@section('title')
    Ruang Sidang
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Ruang Sidang</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ubah Ruang Sidang</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/tim-capstone/ruangan') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left"></i> Kembali</a>
                </small>
            </div>
            <form action="{{ url('/tim-capstone/ruangan/edit-process') }}" method="post" autocomplete="off">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $ruangan->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama Ruang<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_ruang"
                                    value="{{ $ruangan->nama_ruang }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Kode Ruang<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_ruang"
                                    value="{{ $ruangan->kode_ruang }}" required>
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
