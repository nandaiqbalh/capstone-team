@extends('tim_capstone.base.app')

@section('title')
    Penetapan Anggota Kelompok
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">Pendaftaran</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Kelompok</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/tim-capstone/penetapan-anggota') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <form action="{{ url('/tim-capstone/penetapan-anggota/add-process') }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_siklus" value="{{ $rs_siklus->id }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Topik<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $get_topik->nama }}" readonly>
                                <input type="hidden" class="form-control" name="id_topik" value="{{ $get_topik->id }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama Siklus <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_siklus"
                                    value="{{ $rs_siklus->nama_siklus }}" readonly>
                            </div>
                        </div>
                    </div>

                    <p>List Mahasiswa</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Nama Mahasiswa 1<span class="text-danger">*</span></label>
                                <select class="form-select select-2" name="id_mahasiswa1" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($rs_mahasiswa as $mahasiswa)
                                        <option value="{{ $mahasiswa->user_id }}"
                                            @if (old('id_mahasiswa1') == '{{ $mahasiswa->user_id }}') selected @endif>{{ $mahasiswa->user_name }} ||
                                            {{ $mahasiswa->prioritas_peminatan }} ||
                                            {{ $mahasiswa->usulan_judul_capstone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Nama Mahasiswa 2<span class="text-danger">*</span></label>
                                <select class="form-select select-2" name="id_mahasiswa2" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($rs_mahasiswa as $mahasiswa)
                                        <option value="{{ $mahasiswa->user_id }}"
                                            @if (old('id_mahasiswa2') == '{{ $mahasiswa->user_id }}') selected @endif>{{ $mahasiswa->user_name }}
                                            ||
                                            {{ $mahasiswa->prioritas_peminatan }}
                                            ||
                                            {{ $mahasiswa->usulan_judul_capstone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Nama Mahasiswa 3<span class="text-danger">*</span></label>
                                <select class="form-select select-2" name="id_mahasiswa3" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($rs_mahasiswa as $mahasiswa)
                                        <option value="{{ $mahasiswa->user_id }}"
                                            @if (old('id_mahasiswa3') == '{{ $mahasiswa->user_id }}') selected @endif>{{ $mahasiswa->user_name }}
                                            ||
                                            {{ $mahasiswa->prioritas_peminatan }}
                                            ||
                                            {{ $mahasiswa->usulan_judul_capstone }}
                                        </option>
                                    @endforeach
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
