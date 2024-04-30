@extends('tim_capstone.base.app')

@section('title')
    Mahasiswa
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Mahasiswa</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Mahasiswa</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/tim-capstone/mahasiswa') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <form action="{{ url('/tim-capstone/mahasiswa/add-process') }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="user_name" value="{{ old('user_name') }}"
                                    placeholder="Masukkan Nama" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>NIM<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="nomor_induk"
                                    value="{{ old('nomor_induk') }}" placeholder="Masukkan NIM" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Angkatan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="angkatan" value="{{ old('angkatan') }}"
                                    placeholder="Masukkan Angkatan" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Jenis Kelamin<span class="text-danger">*</span></label>
                                <select class="form-select" name="jenis_kelamin" required>
                                    <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Email<span class="text-danger"></span></label>
                                <input type="email" class="form-control" name="user_email" value="{{ old('user_email') }}"
                                    placeholder="Masukkan Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>No Telp<span class="text-danger"></span></label>
                                <input type="tel" class="form-control" name="no_telp" value="{{ old('no_telp') }}"
                                    placeholder="Masukkan No. Telepon">
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
