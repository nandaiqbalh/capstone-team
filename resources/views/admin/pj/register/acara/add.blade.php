
@extends('admin.base.app')

@section('title')
    Acara
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Registrasi /</span> Acara</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tambah Acara</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/pj/register/acara') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/pj/register/acara/add-process') }}" method="post" autocomplete="off" enctype="multipart/form-data">
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
                                        <label >Venue<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="venue" value="{{ old('venue') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Tanggal Mulai<span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Tanggal Selesai<span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Penanggung Jawab<span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="penanggung_jawab" id="">
                                            <option value="" disabled selected>-- Pilih Penanggung Jawab--</option>
                                            @foreach ($rs_user_branch as $user_branch)
                                            <option value="{{$user_branch->user_id}}" @if( old('penanggung_jawab') == '{{$user_branch->user_id}}' ) selected @endif>{{$user_branch->user_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >WA Penanggung Jawab<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="wa_pj" value="{{ old('wa_pj') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Deskripsi<span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="deskripsi" value="{{ old('deskripsi') }}" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Upload Poster<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="poster" value="{{ old('poster') }}" required>
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