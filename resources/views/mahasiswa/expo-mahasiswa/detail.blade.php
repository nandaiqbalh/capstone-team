@extends('tim_capstone.base.app')

@section('title')
    Expo Mahasiswa
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Mahasiswa /</span> Expo</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Expo Project</h5>
            </div>
            <div class="card-body">

                @if ($kelompok != null)
                    @if ($siklus_sudah_punya_kelompok == null)
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                        <h6>Siklus capstone sudah tidak aktif!</h6>
                    @elseif($kelompok->nomor_kelompok == null)
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                        <h6>Kelompok Anda belum valid!</h6>
                    @else
                        @if ($rs_expo == null)
                            <h6>Belum memasuki periode expo!</h6>
                        @else
                            <div class="table-responsive">
                                <table class="table table-borderless table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="20%"></th>
                                            <th width="5%"></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Status</td>
                                            <td>:</td>
                                            @if ($kelompok->status_expo == null)
                                                <td>Belum mendaftar expo!</td>
                                            @else
                                                <td>{{ $kelompok->status_expo }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Siklus</td>
                                            <td>:</td>
                                            @if ($kelompok->nomor_kelompok == null)
                                                <td>-</td>
                                            @else
                                                <td>{{ $rs_expo->tahun_ajaran }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Hari, tanggal</td>
                                            <td>:</td>
                                            @if ($kelompok->nomor_kelompok == null)
                                                <td>-</td>
                                            @else
                                                <td>{{ $rs_expo->hari_expo }}, {{ $rs_expo->tanggal_expo }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Waktu</td>
                                            <td>:</td>
                                            @if ($kelompok->nomor_kelompok == null)
                                                <td>-</td>
                                            @else
                                                <td>{{ $rs_expo->waktu_expo }} WIB</td>
                                            @endif
                                        </tr>

                                        <tr>
                                            <td>Tempat</td>
                                            <td>:</td>
                                            @if ($kelompok->nomor_kelompok == null)
                                                <td>-</td>
                                            @else
                                                <td>{{ $rs_expo->tempat }}</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <br>
                            <br>

                            <h5>Pendaftaran Expo Project</h5>

                            <form action="{{ url('/mahasiswa/expo/expo-daftar') }}" method="post" autocomplete="off">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_expo" value="{{ $rs_expo->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Judul TA Individu<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="judul_ta_mhs"
                                                value="{{ old('judul_ta_mhs', $kelengkapan->judul_ta_mhs) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Link Berkas Expo<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="link_berkas_expo"
                                                value="{{ old('link_upload', $kelompok->link_berkas_expo) }}" required>
                                            @if ($kelompok->link_berkas_expo)
                                                <a target="_blank" href="https://{{ $kelompok->link_berkas_expo }}">
                                                    <p>Lihat Berkas</p>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <button type="submit" class="btn btn-sm btn-primary float-end">Simpan</button>
                            </form>
                        @endif

                    @endif
                @else
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <br>
                    <h6>Anda belum mendaftar capstone!</h6>
                @endif

            </div>
        </div>
    </div>
@endsection
