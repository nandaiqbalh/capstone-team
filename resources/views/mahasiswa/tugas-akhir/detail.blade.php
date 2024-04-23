@extends('tim_capstone.base.app')

@section('title')
    Sidang Tugas Akhir
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Mahasiswa /</span> Sidang Tugas Akhir</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Sidang Tugas Akhir</h5>
            </div>

            <div class="card-body">

                @if ($kelompok != null)

                    @if ($kelompok->nomor_kelompok == null)
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
                        <!-- belum menyelesaikan capstone/ belum valid -->
                        <h6>Anda belum menyelesaikan capstone!</h6>
                    @else
                        <!-- sudah valid kelompoknya -->

                        <!-- check apakah ada periode atau tidak -->
                        @if ($periode == null)
                            <!-- tidak ada periode -->
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
                            <h6>Tidak dalam periode pendaftaran Sidang TA!</h6>
                        @else
                            <!-- ada periode -->
                            <!-- check sudah mandaftar belum -->
                            @if ($status_pendaftaran == null)
                                <!-- tampilkan card periode tersedia, dengan belum mendaftar -->
                                <!-- table info -->
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
                                                <td style="color: {{ $kelompok->status_tugas_akhir_color }}">
                                                    {{ $kelompok->status_tugas_akhir ?: 'Belum Mendaftar Sidang TA!' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Periode</td>
                                                <td>:</td>
                                                @if ($periode->nama_periode == null)
                                                    <td>-</td>
                                                @else
                                                    <td>{{ $periode->nama_periode }}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>Batas Pendaftaran</td>
                                                <td>:</td>
                                                @if ($periode->hari_batas == null)
                                                    <td>-</td>
                                                @else
                                                    <td>{{ $periode->hari_batas }}, {{ $periode->tanggal_batas }}</td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <br>
                                <br>

                                <h5>Pendaftaran Sidang Tugas Akhir</h5>

                                <form id="confirmForm" action="{{ url('/mahasiswa/tugas-akhir/tugas-akhir-daftar') }}"
                                    method="post" autocomplete="off">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id_periode" value="{{ $periode->id }}">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Judul TA Individu<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="judul_ta_mhs"
                                                    value="{{ old('judul_ta_mhs', $data_mahasiswa->judul_ta_mhs) }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Link Berkas Pendukung<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="link_upload"
                                                    value="{{ old('link_upload', $data_mahasiswa->link_upload) }}" required>
                                                @if ($data_mahasiswa->link_upload)
                                                    <a target="_blank" href="https://{{ $data_mahasiswa->link_upload }}">
                                                        <p>Lihat Berkas</p>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#confirmModal"
                                        class="btn btn-sm btn-primary float-end">Simpan</button>
                                </form>
                            @else
                                <!-- ada periode, dengan status sudah mendaftar -->

                                <!-- check jadwal sidang -->
                                @if ($jadwal_sidang == null)
                                    <!-- table info -->
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
                                                    <td style="color: {{ $kelompok->status_tugas_akhir_color }}">
                                                        {{ $kelompok->status_tugas_akhir ?: 'Belum Mendaftar Sidang TA!' }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Periode</td>
                                                    <td>:</td>
                                                    @if ($kelompok->nomor_kelompok == null)
                                                        <td>-</td>
                                                    @else
                                                        <td>{{ $periode->nama_periode }}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>Batas Pendaftaran</td>
                                                    <td>:</td>
                                                    @if ($kelompok->nomor_kelompok == null)
                                                        <td>-</td>
                                                    @else
                                                        <td>{{ $periode->hari_batas }}, {{ $periode->tanggal_batas }}</td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <br>
                                    <br>

                                    <h5>Pendaftaran Sidang Tugas Akhir</h5>

                                    <form action="{{ url('/mahasiswa/tugas-akhir/tugas-akhir-daftar') }}" method="post"
                                        autocomplete="off">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id_periode" value="{{ $periode->id }}">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Judul TA Individu<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="judul_ta_mhs"
                                                        value="{{ old('judul_ta_mhs', $data_mahasiswa->judul_ta_mhs) }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Link Berkas Pendukung<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="link_upload"
                                                        value="{{ old('link_upload', $data_mahasiswa->link_upload) }}"
                                                        required>
                                                    @if ($data_mahasiswa->link_upload)
                                                        <a target="_blank"
                                                            href="https://{{ $data_mahasiswa->link_upload }}">
                                                            <p>Lihat Berkas</p>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <br>
                                        <button type="submit" class="btn btn-sm btn-primary float-end">Simpan</button>
                                    </form>
                                @else
                                    <!-- tampilkan jadwal sidang -->
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
                                                    @if ($kelompok->nomor_kelompok == null)
                                                        <td>Menunggu Persetujuan Tim Capstone!</td>
                                                    @else
                                                        <td>{{ $data_mahasiswa->status_tugas_akhir }}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>Hari, tanggal</td>
                                                    <td>:</td>
                                                    @if ($kelompok->nomor_kelompok == null)
                                                        <td>!</td>
                                                    @else
                                                        <td>{{ $jadwal_sidang->hari_sidang }},
                                                            {{ $jadwal_sidang->tanggal_sidang }}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>Waktu</td>
                                                    <td>:</td>
                                                    @if ($kelompok->nomor_kelompok == null)
                                                        <td>-</td>
                                                    @else
                                                        <td>{{ $jadwal_sidang->waktu_sidang }} WIB</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>Ruang Sidang</td>
                                                    <td>:</td>
                                                    @if ($kelompok->nomor_kelompok == null)
                                                        <td>-</td>
                                                    @else
                                                        <td>{{ $jadwal_sidang->nama_ruang }}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>Judul Capstone</td>
                                                    <td>:</td>
                                                    @if ($kelompok->nomor_kelompok == null)
                                                        <td>-</td>
                                                    @else
                                                        <td>{{ $kelompok->judul_capstone }}</td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <br>
                                    <br>

                                    <h5>Pendaftaran Sidang Tugas Akhir</h5>
                                    <form action="{{ url('/mahasiswa/tugas-akhir/tugas-akhir-daftar') }}" method="post"
                                        autocomplete="off">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id_periode" value="{{ $periode->id }}">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Judul TA Individu<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="judul_ta_mhs"
                                                        value="{{ old('judul_ta_mhs', $data_mahasiswa->judul_ta_mhs) }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Link Berkas Pendukung<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="link_upload"
                                                        value="{{ old('link_upload', $data_mahasiswa->link_upload) }}"
                                                        required>
                                                    @if ($data_mahasiswa->link_upload)
                                                        <a target="_blank"
                                                            href="https://{{ $data_mahasiswa->link_upload }}">
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
                    <h6>Anda belum menyelesaikan capstone!</h6>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin data yang Anda masukkan sudah sesuai?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                    <button type="button" class="btn btn-primary" id="confirmButton">Ya, Yakin</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var confirmButton = document.getElementById('confirmButton');

            confirmButton.addEventListener('click', function() {
                // Lakukan submit formulir secara langsung setelah konfirmasi
                var form = document.getElementById('confirmForm');
                form.submit();
            });
        });
    </script>
@endsection
