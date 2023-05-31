
@extends('admin.base.app')

@section('title')
    Pendaftaran
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4">Pendaftaran</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tambah Kelompok</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/settings/contoh-halaman') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/pendaftaran/add-process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Topik<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{$get_topik->nama}}" readonly>
                                        <input type="hidden" class="form-control" name="id_topik" value="{{ $get_topik->id }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nomor Kelompok<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="nomor_kelompok" value="{{ old('nomor_kelompok') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Pilih Siklus <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_siklus" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_siklus as $siklus)
                                            <option value="{{$siklus->id}}">{{$siklus->tahun_ajaran}} | {{$siklus->tanggal_mulai}} sampai {{$siklus->tanggal_selesai}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Judul TA<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="judul_ta" value="{{ old('judul_ta') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Dosen Pembimbing 1<span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_dosen1" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_dosen as $dosen)
                                                <option value="{{$dosen->user_id}}" @if( old('id_dosen1') == '{{$dosen->user_id}}' ) selected @endif>{{$dosen->user_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Dosen Pembimbing 2<span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_dosen2" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_dosen as $dosen)
                                                <option value="{{$dosen->user_id}}" @if( old('id_dosen2') == '{{$dosen->user_id}}' ) selected @endif>{{$dosen->user_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <p>List Mahasiswa</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label >Nama Mahasiswa 1<span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_mahasiswa1" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_mahasiswa as $mahasiswa)
                                                <option value="{{$mahasiswa->user_id}}" @if( old('id_mahasiswa1') == '{{$mahasiswa->user_id}}' ) selected @endif>{{$mahasiswa->user_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label >Nama Mahasiswa 2<span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_mahasiswa2" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_mahasiswa as $mahasiswa)
                                                <option value="{{$mahasiswa->user_id}}" @if( old('id_mahasiswa2') == '{{$mahasiswa->user_id}}' ) selected @endif>{{$mahasiswa->user_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label >Nama Mahasiswa 3<span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_mahasiswa3" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_mahasiswa as $mahasiswa)
                                                <option value="{{$mahasiswa->user_id}}" @if( old('id_mahasiswa3') == '{{$mahasiswa->user_id}}' ) selected @endif>{{$mahasiswa->user_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary float-end">Simpan</button>                            
                        </form>


                        
                    </div>
                </div>
            </div>
@endsection