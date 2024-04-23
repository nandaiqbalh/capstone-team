@extends('tim_capstone.base.app')

@section('title')
    Penjadwalan Sidang Tugas Akhir
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">Penjadwalan Sidang Tugas Akhir</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Data Mahasiswa</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/sidang-ta') }}" class="btn btn-secondary btn-xs float-right"><i
                            class="bx bx-chevron-left"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
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
                                <td>Nama Mahasiswa</td>
                                <td>:</td>

                                @if ($mahasiswa->user_name == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->user_name }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>NIM</td>
                                <td>:</td>

                                @if ($mahasiswa->nomor_induk == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->nomor_induk }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Nomor Kelompok</td>
                                <td>:</td>

                                @if ($mahasiswa->nomor_kelompok == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->nomor_kelompok }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Status Tugas Akhir</td>
                                <td>:</td>

                                @if ($mahasiswa->status_tugas_akhir == null)
                                    <td>-</td>
                                @else
                                    <td style="color: {{ $mahasiswa->status_sidang_color }}">
                                        {{ $mahasiswa->status_tugas_akhir }}</td>
                                @endif
                            </tr>

                            <tr>
                                <td>Hari, tanggal</td>
                                <td>:</td>

                                @if ($jadwal_sidang == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $jadwal_sidang->hari_sidang }}, {{ $jadwal_sidang->tanggal_sidang }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td>:</td>

                                @if ($jadwal_sidang == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $jadwal_sidang->waktu_sidang }} WIB - {{ $jadwal_sidang->waktu_selesai }} WIB
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>Tempat</td>
                                <td>:</td>

                                @if ($jadwal_sidang == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $jadwal_sidang->nama_ruang }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Judul Tugas Akhir</td>
                                <td>:</td>

                                @if ($mahasiswa->judul_ta_mhs == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->judul_ta_mhs }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Topik</td>
                                <td>:</td>

                                @if ($mahasiswa->nama_topik == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->nama_topik }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
                <hr>

                <br>

                <h6 class="mb-0">List Dosen Penguji TA</h6>
                <br>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Penguji</th>
                                <th>NIP/NIDN</th>
                                <th>Posisi</th>
                                <th>Status Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_penguji_ta->count() > 0)
                                @foreach ($rs_penguji_ta as $index => $penguji_sidangta)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $penguji_sidangta->user_name }}</td>
                                        <td>{{ $penguji_sidangta->nomor_induk }}</td>
                                        <td>{{ $penguji_sidangta->jenis_dosen }}</td>
                                        @if ($penguji_sidangta->jenis_dosen == 'Penguji 1')
                                            <td style="color: {{ $mahasiswa->status_penguji1_color }}">
                                                {{ $penguji_sidangta->status_dosen }}</td>
                                        @elseif($penguji_sidangta->jenis_dosen == 'Penguji 2')
                                            <td style="color: {{ $mahasiswa->status_penguji2_color }}">
                                                {{ $penguji_sidangta->status_dosen }}</td>
                                        @else
                                            <td>-</td>
                                        @endif

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

                <hr>

                {{-- upload file mhs  --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <h5 class="card-header">Laporan TA</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <i class='bx bxs-file-doc bx-lg'></i>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control"
                                            value="{{ $mahasiswa->file_name_laporan_ta }}" readonly>
                                        <a href="{{ url('/file/mahasiswa/laporan-ta') }}/{{ $mahasiswa->file_name_laporan_ta }}"
                                            class="btn btn-primary float-end m-1 btn-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <h5 class="card-header">Makalah TA</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <i class='bx bxs-file-doc bx-lg'></i>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control"
                                            value="{{ $mahasiswa->file_name_makalah }}" readonly>
                                        <a href="{{ url('/file/mahasiswa/makalah') }}/{{ $mahasiswa->file_name_makalah }}"
                                            class="btn btn-primary float-end m-1 btn-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <hr>
                {{-- upload file end  --}}
                <h6 class="mb-0">List Dosen Pembimbing</h6>
                <br>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Dosbing</th>
                                <th>NIP/NIDN</th>
                                <th>Posisi</th>
                                <th>Status Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_dosbing->count() > 0)
                                @foreach ($rs_dosbing as $index => $dosbing)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $dosbing->user_name }}</td>
                                        <td>{{ $dosbing->nomor_induk }}</td>
                                        <td>{{ $dosbing->jenis_dosen }}</td>
                                        @if ($dosbing->jenis_dosen == 'Pembimbing 1')
                                            <td style="color: {{ $dosbing->status_pembimbing1_color }}">
                                                {{ $mahasiswa->file_status_lta_dosbing1 }}</td>
                                        @elseif ($dosbing->jenis_dosen == 'Pembimbing 2')
                                            <td style="color: {{ $dosbing->status_pembimbing2_color }}">
                                                {{ $mahasiswa->file_status_lta_dosbing2 }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
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

                <br>

            </div>

        </div>
    </div>

@endsection
