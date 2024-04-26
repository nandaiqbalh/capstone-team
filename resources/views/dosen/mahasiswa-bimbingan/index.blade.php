@extends('tim_capstone.base.app')

@section('title')
    Mahasiswa Bimbingan
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Mahasiswa Bimbingan</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Mahasiswa Bimbingan</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/dosen/mahasiswa-bimbingan/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="nama"
                                        value="{{ !empty($nama) ? $nama : '' }}" placeholder="Nama Mahasiswa" minlength="3"
                                        required>
                                </div>
                                <div class="col-auto mt-1">
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="search">
                                        <i class="bx bx-search-alt-2"></i>
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>

                <div class="row">
                    <form action="{{ url('/dosen/mahasiswa-bimbingan/filter-status') }}" method="get" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-8"> <!-- Menyesuaikan dengan lebar yang diinginkan -->
                                <div class="mb-3">
                                    <select class="form-select select-2" name="status" required>
                                        <option value="" disabled selected>-- Filter Status --</option>
                                        <option value="0" {{ isset($status) && $status == '0' ? 'selected' : '' }}>
                                            Belum Lulus</option>
                                        <option value="1" {{ isset($status) && $status == '1' ? 'selected' : '' }}>
                                            Sudah Lulus</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3"><!-- Menyesuaikan dengan lebar yang diinginkan -->
                                    <button type="submit" class="btn btn-primary float-end" name="action"
                                        value="filter">Terapkan Filter</button>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Status Mahasiswa</th>
                                <th>Siklus Pendaftaran</th>
                                <th>Posisi Pembimbing</th>
                                <th>Lulus</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_bimbingan_saya->count() > 0)
                                @foreach ($rs_bimbingan_saya as $index => $mahasiswa)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_bimbingan_saya->firstItem() }}.</td>
                                        <td>{{ $mahasiswa->user_name }}</td>
                                        <td style="color: {{ $mahasiswa->status_color }}">{{ $mahasiswa->status_individu }}
                                        </td>
                                        <td>{{ $mahasiswa->nama_siklus }}</td>
                                        <td>{{ $mahasiswa->jenis_dosen }}</td>
                                        <td>
                                            @if ($mahasiswa->is_selesai == 1)
                                                <span style="color: #44B158">Lulus!</span>
                                            @else
                                                <span style="color: #FF0000">Belum Lulus!</span>
                                            @endif
                                        </td>
                                        <td class="text-center">

                                            <a href="{{ url('/dosen/mahasiswa-bimbingan/detail-mahasiswa') }}/{{ $mahasiswa->id_mahasiswa }}"
                                                class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="6">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto mr-auto">
                        <p>Menampilkan {{ $rs_bimbingan_saya->count() }} dari total {{ $rs_bimbingan_saya->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_bimbingan_saya->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
