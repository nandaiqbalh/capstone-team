
@extends('admin.base.app')

@section('title')
    Sidang Proposal
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Jadwal Sidang Proposal</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tambah Jadwal Sidang Proposal</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/jadwal-pendaftaran/sidang-proposal') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/jadwal-pendaftaran/sidang-proposal/add-process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Pilih Siklus <span class="text-danger">*</span></label>
                                        <select class="form-select" name="siklus_id" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_siklus as $siklus)
                                            <option value="{{$siklus->id}}">{{$siklus->tahun_ajaran}} | {{$siklus->tanggal_mulai}} sampai {{$siklus->tanggal_selesai}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Pilih Kelompok <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_kelompok" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_kelompok as $kelompok)
                                            <option value="{{$kelompok->id}}">{{$kelompok->nomor_kelompok}} | {{$kelompok->judul_capstone}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Pilih Dosen Penguji 1 <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_dosen_1" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_dosen as $dosen)
                                            <option value="{{$dosen->user_id}}">{{$dosen->user_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Pilih Dosen Penguji 2 <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_dosen_2" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_dosen as $dosen)
                                            <option value="{{$dosen->user_id}}">{{$dosen->user_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>  --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Tanggal<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Waktu<span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="waktu" value="{{ old('waktu') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Ruangan<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ruangan" value="{{ old('ruangan') }}" required>
                                    </div>
                                </div>
                            </div>
                            <br>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-primary float-end">Simpan</button>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
@endsection
