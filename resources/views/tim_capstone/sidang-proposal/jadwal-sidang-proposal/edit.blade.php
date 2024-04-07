@extends('tim_capstone.base.app')

@section('title')
    Sidang Proposal
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Jadwal Sidang Proposal</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Jadwal Sidang Proposal</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/jadwal-pendaftaran/sidang-proposal') }}"
                        class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/jadwal-pendaftaran/sidang-proposal/edit-process') }}" method="post"
                    autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $jadwalSidang->id }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Pilih Siklus <span class="text-danger">*</span></label>
                                <select class="form-select" name="siklus_id" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($rs_siklus as $siklus)
                                        <option value="{{ $siklus->id }}"
                                            @if ($siklus->id == $jadwalSidang->siklus_id) selected @endif>{{ $siklus->nama_siklus }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Pilih Kelompok <span class="text-danger">*</span></label>
                                <select class="form-select select-2" name="id_kelompok" required>
                                    <option value="{{ $jadwalSidang->id_kelompok }}" selected>
                                        {{ $jadwalSidang->nomor_kelompok }}</option>
                                    @foreach ($rs_kelompok as $kelompok)
                                        <option value="{{ $kelompok->id }}"
                                            @if ($kelompok->id == $jadwalSidang->id_kelompok) selected @endif>
                                            {{ $kelompok->nomor_kelompok }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tanggal<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_mulai"
                                    value="{{ old('tanggal_mulai', $jadwalSidang->tanggal_mulai) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Waktu<span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="waktu"
                                    value="{{ old('waktu', $jadwalSidang->waktu) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Ruangan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ruangan"
                                    value="{{ old('ruangan', $jadwalSidang->ruangan) }}" required>
                            </div>
                        </div>
                    </div>
                    <br>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection
