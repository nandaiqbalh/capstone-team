@extends('tim_capstone.base.app')

@section('title')
    Sidang Tugas Akhir
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">Sidang Tugas Akhir</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pendaftar Sidang Tugas Akhir</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/sidang-ta') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left"></i> Kembali</a>
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
                                <td>Nama Periode</td>
                                <td>:</td>
                                <td>{{ $sidang_ta->nama_periode }}</td>
                            </tr>
                            <tr>
                                <td>Batas Pendaftaran</td>
                                <td>:</td>
                                <td>{{ $sidang_ta->tanggal_selesai }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <hr>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nomor Kelompok</th>
                                <th>Nama Mahasiswa</th>
                                <th>Status Sidang</th>
                                <th>Link Berkas</th>
                                <th>Status Pendaftaran</th>
                                <th>Status Penguji 1</th>
                                <th>Status Penguji 2</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_pendaftar_sidangta->count() > 0)
                                @foreach ($rs_pendaftar_sidangta as $index => $pendaftaran)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $pendaftaran->nomor_kelompok }}</td>
                                        <td>{{ $pendaftaran->user_name }}</td>
                                        <td style ="color: {{ $pendaftaran->color_sidangta }}">
                                            {{ $pendaftaran->status_tugas_akhir }}</td>
                                        <td><a href="{{ $pendaftaran->link_upload }}"
                                                style="text-decoration: underline; color: blue;" target="_blank">Link
                                                berkas</a></td>
                                        <td class="text-center">
                                            @if ($pendaftaran->status_tugas_akhir == 'Menunggu Persetujuan Berkas TA!')
                                                <a href="{{ url('/admin/sidang-ta/terima') }}/{{ $pendaftaran->id_mahasiswa }}"
                                                    class="btn btn-outline-primary btn-xs m-1 "onclick="return confirm('Apakah anda ingin menerima {{ $pendaftaran->user_name }} ?')">
                                                    Terima</a>
                                                <a href="{{ url('/admin/sidang-ta/tolak') }}/{{ $pendaftaran->id_mahasiswa }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda ingin menolak {{ $pendaftaran->user_name }} ?')">
                                                    Tolak</a>
                                            @elseif($pendaftaran->status_tugas_akhir == 'Menunggu Penjadwalan Sidang TA!')
                                                <a href="{{ url('/admin/sidang-ta/tolak') }}/{{ $pendaftaran->id_mahasiswa }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda ingin menolak {{ $pendaftaran->user_name }} ?')">
                                                    Tolak</a>
                                            @elseif($pendaftaran->status_tugas_akhir == 'Berkas TA Tidak Disetujui!')
                                                <a href="{{ url('/admin/sidang-ta/terima') }}/{{ $pendaftaran->id_mahasiswa }}"
                                                    class="btn btn-outline-primary btn-xs m-1 "onclick="return confirm('Apakah anda ingin menerima {{ $pendaftaran->user_name }} ?')">
                                                    Terima</a>
                                            @else
                                                @if (
                                                    $pendaftaran->status_tugas_akhir == 'Lulus Sidang TA!' ||
                                                        $pendaftaran->status_tugas_akhir == 'Gagal Sidang TA!' ||
                                                        $pendaftaran->status_tugas_akhir == 'Telah Dijadwalkan Sidang TA!' ||
                                                        $pendaftaran->status_tugas_akhir == 'Menunggu Persetujuan Penguji!')
                                                    <span style="color: #44B158">Disetujui!</span>
                                                @else
                                                    <span style="color: #FF0000">Tidak Disetujui!</span>
                                                @endif
                                            @endif
                                        </td>
                                        @if ($pendaftaran->status_dosen_penguji_ta1 == null)
                                            <td>-</td>
                                        @else
                                            <td style="color: {{ $pendaftaran->status_color_penguji1 }}">
                                                {{ $pendaftaran->status_dosen_penguji_ta1 }}</td>
                                        @endif
                                        @if ($pendaftaran->status_dosen_penguji_ta2 == null)
                                            <td>-</td>
                                        @else
                                            <td style="color: {{ $pendaftaran->status_color_penguji2 }}">
                                                {{ $pendaftaran->status_dosen_penguji_ta2 }}</td>
                                        @endif

                                        @if (
                                            $pendaftaran->status_tugas_akhir == 'Berkas TA Tidak Disetujui!' ||
                                                $pendaftaran->status_tugas_akhir == 'Menunggu Persetujuan Berkas TA!')
                                            <td>-</td>
                                        @else
                                            @if (
                                                $pendaftaran->status_tugas_akhir == 'Menunggu Penjadwalan Sidang TA!' ||
                                                    $pendaftaran->status_tugas_akhir == 'Penguji Ditetapkan!')
                                                <td class="text-center">
                                                    <a href="{{ url('/admin/sidang-ta/jadwalkan-sidang-ta') }}/{{ $pendaftaran->id_mahasiswa }}/{{ $sidang_ta->id }}"
                                                        class="btn btn-outline-primary btn-xs m-1 ">Jadwalkan Sidang</a>
                                                </td>
                                            @elseif($pendaftaran->status_tugas_akhir == 'Dijadwalkan Sidang TA!')
                                                <td class="text-center">
                                                    <a href="{{ url('/admin/jadwal-sidang-ta/detail') }}/{{ $pendaftaran->id }}"
                                                        class="btn btn-outline-secondary btn-xs m-1 ">Detail</a>
                                                </td>
                                            @elseif($pendaftaran->status_tugas_akhir == 'Lulus Sidang TA!')
                                                <td class="text-center">
                                                    -
                                                </td>
                                            @else
                                                <td class="text-center">
                                                    <a href="{{ url('/admin/sidang-ta/jadwalkan-sidang-ta') }}/{{ $pendaftaran->id_mahasiswa }}/{{ $sidang_ta->id }}"
                                                        class="btn btn-outline-warning btn-xs m-1 ">Ubah</a>
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="9">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
