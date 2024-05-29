@extends('tim_capstone.base.app')

@section('title')
    Dosen
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Dosen</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Dosen</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/tim-capstone/dosen') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <form action="{{ url('/tim-capstone/dosen/add-process') }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" value="{{ old('nama') }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>NIP<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nip" value="{{ old('nip') }}"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Role <span class="text-danger">*</span></label>
                                <select class="form-select" name="role_id" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="02" @if (old('role_id') == '02') selected @endif>Tim Capstone
                                    </option>
                                    <option value="04" @if (old('role_id') == '04') selected @endif>Dosen</option>
                                </select>
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
                    </div>

                    <br>
                    <button type="submit" class="btn btn-primary float-end">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
