@extends('tim_capstone.base.app')

@section('title')
    Expo
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">Expo</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Expo</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/expo-project') }}" class="btn btn-secondary btn-xs float-right"><i
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
                                <td>Siklus </td>
                                <td>:</td>
                                <td>{{ $expo->tahun_ajaran }}</td>
                            </tr>
                            <tr>
                                <td>Tempat </td>
                                <td>:</td>
                                <td>{{ $expo->tempat }}</td>
                            </tr>
                            <tr>
                                <td>Hari, tanggal</td>
                                <td>:</td>
                                <td>{{ $expo->hari_expo }}, {{ $expo->tanggal_expo }}</td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td>:</td>
                                <td>{{ $expo->waktu_expo }} WIB</td>
                            </tr>
                            <tr>
                                <td>Batas Pendaftaran</td>
                                <td>:</td>
                                <td>{{ $expo->tanggal_selesai }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nomor Kelompok</th>
                                <th>Status</th>
                                <th>Berkas</th>
                                <th>Status Pendaftaran</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_kel_expo->count() > 0)
                                @foreach ($rs_kel_expo as $index => $pendaftaran)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $pendaftaran->nomor_kelompok }}</td>
                                        <td>{{ $pendaftaran->status_kelompok }}</td>
                                        <td><a href="{{ $pendaftaran->link_berkas_expo }}"
                                                style="text-decoration: underline; color: blue;" target="_blank">Link
                                                berkas</a></td>

                                        <td class="text-center">

                                            @if ($pendaftaran->status_kelompok == 'Menunggu Validasi Expo!')
                                                <a href="{{ url('/admin/expo-project/terima') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-primary btn-xs m-1 "onclick="return confirm('Apakah anda ingin menerima {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Terima</a>
                                                <a href="{{ url('/admin/expo-project/tolak') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda ingin menolak {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Tolak</a>
                                            @elseif($pendaftaran->status_kelompok == 'Validasi Expo Berhasil!')
                                                <a href="{{ url('/admin/expo-project/tolak') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda ingin menolak {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Tolak</a>
                                            @elseif($pendaftaran->status_kelompok == 'Validasi Expo Gagal!')
                                                <a href="{{ url('/admin/expo-project/terima') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-primary btn-xs m-1 "onclick="return confirm('Apakah anda ingin menerima {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Terima</a>
                                            @else
                                                <a href="{{ url('/admin/expo-project/terima') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-primary btn-xs m-1 "onclick="return confirm('Apakah anda ingin menerima {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Terima</a>
                                                <a href="{{ url('/admin/expo-project/tolak') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda ingin menolak {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Tolak</a>
                                            @endif
                                        </td>

                                        <td class="text-center">

                                            @if ($pendaftaran->status_kelompok == 'Lulus Expo Project!')
                                                <a href="{{ url('/admin/expo-project/to-gagal') }}/{{ $pendaftaran->id_kelompok }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda yakin kelompok {{ $pendaftaran->nomor_kelompok }} tidak lulus?')">
                                                    Gagal</a>
                                            @elseif($pendaftaran->status_kelompok == 'Gagal Expo Project!')
                                                <a href="{{ url('/admin/expo-project/to-lulus') }}/{{ $pendaftaran->id_kelompok }}"
                                                    class="btn btn-outline-primary btn-xs m-1">Lulus</a>
                                            @else
                                                <a href="{{ url('/admin/expo-project/to-gagal') }}/{{ $pendaftaran->id_kelompok }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda yakin kelompok {{ $pendaftaran->nomor_kelompok }} tidak lulus?')">
                                                    Gagal</a>
                                                <a href="{{ url('/admin/expo-project/to-lulus') }}/{{ $pendaftaran->id_kelompok }}"
                                                    class="btn btn-outline-primary btn-xs m-1">Lulus</a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection