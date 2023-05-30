
@extends('admin.base.app')

@section('title')
    Siklus
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Siklus</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Siklus</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/siklus') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/siklus/edit-process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <input type="hidden" name="id" value="{{ $siklus->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label >Nama - Tahun Ajaran<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tahun_ajaran" value="{{ $siklus->tahun_ajaran }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Tanggal Mulai<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_mulai" value="{{ $siklus->tanggal_mulai }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label >Tanggal Selesai<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_selesai" value="{{ $siklus->tanggal_selesai }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            <option value="aktif" @if( old('status',$siklus->status) == 'aktif' ) selected @endif>aktif</option>
                                            <option value="tidak aktif" @if( old('status',$siklus->status) == 'tidak aktif' ) selected @endif>tidak aktif</option>
                                        </select>
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