
@extends('tim_capstone.base.app')

@section('title')
    Broadcast
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Broadcast</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Broadcast</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/broadcast') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/broadcast/edit-process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <input type="hidden" name="id" value="{{ $broadcast->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Nama Event<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_event" value="{{ $broadcast->nama_event }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Keterangan<span class="text-danger"></span></label>
                                        <input type="text" class="form-control" name="keterangan" value="{{ $broadcast->keterangan }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Tanggal Mulai<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tgl_mulai" value="{{ $broadcast->tgl_mulai }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Tanggal Selesai<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tgl_selesai" value="{{ $broadcast->tgl_selesai }}" required>
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
