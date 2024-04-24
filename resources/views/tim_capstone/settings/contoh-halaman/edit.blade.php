
@extends('tim_capstone.base.app')

@section('title')
    Contoh Halaman
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Contoh Halaman</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Contoh Halaman</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/settings/contoh-halaman') }}" class="btn btn-danger btn-sm float-right"><i 
                                class="fas fa-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/settings/contoh-halaman/edit-process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <input type="hidden" name="id" value="{{ $ch->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Nama<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" value="{{ $ch->nama }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jenis_kelamin" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            <option value="L" @if( $ch->jenis_kelamin == 'L' ) selected @endif>Laki - laki</option>
                                            <option value="P" @if( $ch->jenis_kelamin == 'P' ) selected @endif>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                        </div>

                            <br>
                            <button type="submit" class="btn btn-primary float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
@endsection
