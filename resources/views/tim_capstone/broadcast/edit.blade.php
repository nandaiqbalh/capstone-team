@extends('tim_capstone.base.app')

@section('title')
    Broadcast
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('ijaboCropTool/ijaboCropTool.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Broadcast</h5>
        <!-- notification -->
        @include("template.notification")

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ubah Broadcast</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/broadcast') }}" class="btn btn-danger btn-sm float-right"><i class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <form action="{{ url('/admin/broadcast/edit-process') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                <div class="card-body">
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
                                <label >Link Pendukung</label>
                                <input type="text" class="form-control" name="link_pendukung" value="{{ $broadcast->link_pendukung }}" placeholder="Contoh: google.com" >
                                <small class="text-muted">Masukkan URL tanpa https://</small>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                {{-- <label >Keterangan<span class="text-danger"></span></label>
                                <input type="text" class="form-control" name="keterangan" value="{{ $broadcast->keterangan }}"> --}}
                                <div class="mb-3">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea id="keterangan" name="keterangan">{{ old('keterangan', $broadcast->keterangan) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Load Bootstrap script -->
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                    <!-- Load Summernote script -->
                    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
                    <script>
                        // Inisialisasi Summernote
                        $(document).ready(function() {
                            $('#keterangan').summernote();
                        });
                    </script>
                </div>
                <div class="card-footer float-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
