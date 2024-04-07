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
                                            <td>
                                                @php
                                                    $statusKelompok = $kelompok->status_expo;
                                                    $color = '';

                                                    switch ($statusKelompok) {
                                                        case 'Menunggu Penetapan Kelompok!':
                                                        case 'Menunggu Penetapan Dosbing!':
                                                        case 'Menunggu Persetujuan Anggota!':
                                                        case 'Menunggu Persetujuan Dosbing!':
                                                        case 'Menunggu Persetujuan Penguji!':
                                                        case 'Menunggu Validasi Kelompok!':
                                                        case 'Menunggu Validasi Expo!':
                                                            $color = '#F86F03'; // Warna Orange
                                                            break;
                                                        case 'Validasi Kelompok Berhasil!':
                                                        case 'C100 Telah Disetujui!':
                                                        case 'Penguji Proposal Ditetapkan!':
                                                        case 'Dijadwalkan Sidang Proposal!':
                                                        case 'Persetujuan Penguji Berhasil!':
                                                        case 'Lulus Sidang Proposal!':
                                                        case 'C200 Telah Disetujui!':
                                                        case 'C300 Telah Disetujui!':
                                                        case 'C400 Telah Disetujui!':
                                                        case 'C500 Telah Disetujui!':
                                                        case 'Validasi Expo Berhasil!':
                                                        case 'Lulus Expo Project!':
                                                        case 'Lulus Capstone Project!':
                                                            $color = '#44B158'; // Warna Hijau
                                                            break;
                                                        default:
                                                            $color = '#FF0000';
                                                            // Warna Merah
                                                            break;
                                                    }
                                                @endphp

                                                <span style="color: {{ $color }};">
                                                    {{ $statusKelompok ?? 'Belum Mendaftar Expo Project!' }}
                                                </span>
                                            </td>
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

                            <form id="confirmForm" action="{{ url('/mahasiswa/expo/expo-daftar') }}" method="post"
                                autocomplete="off">
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
                                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">Daftar</button>
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
