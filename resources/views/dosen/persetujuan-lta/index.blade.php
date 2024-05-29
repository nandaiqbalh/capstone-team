@extends('tim_capstone.base.app')

@section('title')
    Persetujuan Dokumen Laporan TA
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Persetujuan Dokumen Laporan TA</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Persetujuan Dokumen Laporan TA</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/dosen/persetujuan-lta/search') }}" method="get"
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

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Status Dokumen Laporan TA</th>
                                <th>Dokumen Laporan TA</th>
                                <th>Posisi Dosen</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_persetujuan_lta->count() > 0)
                                @foreach ($rs_persetujuan_lta as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_persetujuan_lta->firstItem() }}.</td>
                                        <td>{{ $kelompok->user_name }}</td>
                                        <td style="color: {{ $kelompok->status_dokumen_color }}">
                                            {{ $kelompok->file_status_lta }}</td>
                                        <td>
                                            @if ($kelompok->file_path_laporan_ta && $kelompok->file_name_laporan_ta)
                                                <a href="{{ asset($kelompok->file_path_laporan_ta . '/' . $kelompok->file_name_laporan_ta) }}"
                                                    target="_blank">
                                                    Lihat File
                                                </a>
                                            @else
                                                File tidak tersedia
                                            @endif
                                        </td>
                                        <td>{{ $kelompok->jenis_dosen }}</td>

                                        <td class="text-center">

                                            @if ($kelompok->jenis_dosen == 'Pembimbing 1')
                                                @if (
                                                    $kelompok->file_status_lta_dosbing1 == 'Laporan TA Telah Disetujui' ||
                                                        $kelompok->file_status_lta_dosbing1 == 'Final Laporan TA Telah Disetujui')
                                                @elseif(
                                                    $kelompok->file_status_lta_dosbing1 == 'Menunggu Persetujuan Laporan TA' ||
                                                        $kelompok->file_status_lta_dosbing1 == 'Menunggu Persetujuan Final Laporan TA')
                                                    <a href="{{ url('/dosen/persetujuan-lta/terima') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-success btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-lta/terima') }}/{{ $kelompok->id }}')">
                                                        Terima</a>
                                                    <a href="{{ url('/dosen/persetujuan-lta/tolak') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-danger btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-lta/tolak') }}/{{ $kelompok->id }}')">
                                                        Tolak</a>
                                                @elseif(
                                                    $kelompok->file_status_lta_dosbing1 == 'Laporan TA Tidak Disetujui Dosbing 1' ||
                                                        $kelompok->file_status_lta_dosbing1 == 'Final Laporan TA Tidak Disetujui Dosbing 1')
                                                    <a href="{{ url('/dosen/persetujuan-lta/terima') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-success btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-lta/terima') }}/{{ $kelompok->id }}')">
                                                        Terima</a>
                                                @else
                                                    <a href="{{ url('/dosen/mahasiswa-bimbingan/detail-mahasiswa') }}/{{ $kelompok->id_mahasiswa }}"
                                                        class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                                @endif
                                            @else
                                                @if (
                                                    $kelompok->file_status_lta_dosbing2 == 'Laporan TA Telah Disetujui' ||
                                                        $kelompok->file_status_lta_dosbing2 == 'Final Laporan TA Telah Disetujui')
                                                @elseif(
                                                    $kelompok->file_status_lta_dosbing2 == 'Menunggu Persetujuan Laporan TA' ||
                                                        $kelompok->file_status_lta_dosbing2 == 'Menunggu Persetujuan Final Laporan TA')
                                                    <a href="{{ url('/dosen/persetujuan-lta/terima') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-success btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-lta/terima') }}/{{ $kelompok->id }}')">
                                                        Terima</a>
                                                    <a href="{{ url('/dosen/persetujuan-lta/tolak') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-danger btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-lta/tolak') }}/{{ $kelompok->id }}')">
                                                        Tolak</a>
                                                @elseif(
                                                    $kelompok->file_status_lta_dosbing2 == 'Laporan TA Tidak Disetujui Dosbing 2' ||
                                                        $kelompok->file_status_lta_dosbing2 == 'Final Laporan TA Tidak Disetujui Dosbing 2')
                                                    <a href="{{ url('/dosen/persetujuan-lta/terima') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-success btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-lta/terima') }}/{{ $kelompok->id }}')">
                                                        Terima</a>
                                                @else
                                                @endif
                                            @endif
                                            <a href="{{ url('/dosen/mahasiswa-bimbingan/detail-mahasiswa') }}/{{ $kelompok->id_mahasiswa }}"
                                                class="btn btn-outline-secondary btn-xs m-1"> Detail</a>

                                        </td>

                                        <script>
                                            function swalConfirm(namaMahasiswa, url) {
                                                Swal.fire({
                                                    title: 'Apakah Anda yakin?',
                                                    text: "Anda akan melakukan tindakan pada " + namaMahasiswa + "?",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Ya, Lanjutkan',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = url;
                                                    }
                                                });
                                            }
                                        </script>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="8">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto mr-auto">
                        <p>Menampilkan {{ $rs_persetujuan_lta->count() }} dari total
                            {{ $rs_persetujuan_lta->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_persetujuan_lta->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
