@extends('tim_capstone.base.app')

@section('title')
    Persetujuan Dokumen C100
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Persetujuan Dokumen C100</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Persetujuan Dokumen C100</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/dosen/persetujuan-c100/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="nama"
                                        value="{{ !empty($nama) ? $nama : '' }}" placeholder="Nomor Kelompok" minlength="3"
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
                                <th>Nomor Kelompok</th>
                                <th>Status Dokumen C100</th>
                                <th>Dokumen C100</th>
                                <th>Posisi Dosen</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_persetujuan_100->count() > 0)
                                @foreach ($rs_persetujuan_100 as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_persetujuan_100->firstItem() }}.</td>
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                        <td style="color: {{ $kelompok->status_dokumen_color }}">
                                            {{ $kelompok->file_status_c100 }}</td>
                                        <td>
                                            @if ($kelompok->file_path_c100 && $kelompok->file_name_c100)
                                                <a href="{{ asset($kelompok->file_path_c100 . '/' . $kelompok->file_name_c100) }}"
                                                    target="_blank">
                                                    Lihat File C100
                                                </a>
                                            @else
                                                File tidak tersedia
                                            @endif
                                        </td>
                                        <td>{{ $kelompok->jenis_dosen }}</td>

                                        <td class="text-center">

                                            @if ($kelompok->is_lulus_expo == 1)
                                                <a href="{{ url('/dosen/kelompok-bimbingan/detail') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                            @else
                                                @if ($kelompok->jenis_dosen == 'Pembimbing 1')
                                                    @if (
                                                        $kelompok->file_status_c100_dosbing1 == 'C100 Telah Disetujui' ||
                                                            $kelompok->file_status_c100_dosbing1 == 'Final C100 Telah Disetujui')
                                                        <a href="{{ url('/dosen/persetujuan-c100/tolak') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-danger btn-xs m-1"
                                                            onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/persetujuan-c100/tolak') }}/{{ $kelompok->id }}')">
                                                            Tolak</a>
                                                    @elseif(
                                                        $kelompok->file_status_c100_dosbing1 == 'Menunggu Persetujuan C100' ||
                                                            $kelompok->file_status_c100_dosbing1 == 'Menunggu Persetujuan Final C100')
                                                        <a href="{{ url('/dosen/persetujuan-c100/terima') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-success btn-xs m-1"
                                                            onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/persetujuan-c100/terima') }}/{{ $kelompok->id }}')">
                                                            Terima</a>
                                                        <a href="{{ url('/dosen/persetujuan-c100/tolak') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-danger btn-xs m-1"
                                                            onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/persetujuan-c100/tolak') }}/{{ $kelompok->id }}')">
                                                            Tolak</a>
                                                    @elseif(
                                                        $kelompok->file_status_c100_dosbing1 == 'C100 Tidak Disetujui Dosbing 1' ||
                                                            $kelompok->file_status_c100_dosbing1 == 'Final C100 Tidak Disetujui Dosbing 1')
                                                        <a href="{{ url('/dosen/persetujuan-c100/terima') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-success btn-xs m-1"
                                                            onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/persetujuan-c100/terima') }}/{{ $kelompok->id }}')">
                                                            Terima</a>
                                                    @else
                                                        <a href="{{ url('/dosen/kelompok-bimbingan/detail') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                                    @endif
                                                @else
                                                    @if (
                                                        $kelompok->file_status_c100_dosbing2 == 'C100 Telah Disetujui' ||
                                                            $kelompok->file_status_c100_dosbing2 == 'Final C100 Telah Disetujui')
                                                        <a href="{{ url('/dosen/persetujuan-c100/tolak') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-danger btn-xs m-1"
                                                            onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/persetujuan-c100/tolak') }}/{{ $kelompok->id }}')">
                                                            Tolak</a>
                                                    @elseif(
                                                        $kelompok->file_status_c100_dosbing2 == 'Menunggu Persetujuan C100' ||
                                                            $kelompok->file_status_c100_dosbing2 == 'Menunggu Persetujuan Final C100')
                                                        <a href="{{ url('/dosen/persetujuan-c100/terima') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-success btn-xs m-1"
                                                            onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/persetujuan-c100/terima') }}/{{ $kelompok->id }}')">
                                                            Terima</a>
                                                        <a href="{{ url('/dosen/persetujuan-c100/tolak') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-danger btn-xs m-1"
                                                            onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/persetujuan-c100/tolak') }}/{{ $kelompok->id }}')">
                                                            Tolak</a>
                                                    @elseif(
                                                        $kelompok->file_status_c100_dosbing2 == 'C100 Tidak Disetujui Dosbing 2' ||
                                                            $kelompok->file_status_c100_dosbing2 == 'Final C100 Tidak Disetujui Dosbing 2')
                                                        <a href="{{ url('/dosen/persetujuan-c100/terima') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-success btn-xs m-1"
                                                            onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/persetujuan-c100/terima') }}/{{ $kelompok->id }}')">
                                                            Terima</a>
                                                    @else
                                                        <a href="{{ url('/dosen/kelompok-bimbingan/detail') }}/{{ $kelompok->id }}"
                                                            class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                                    @endif
                                                @endif
                                            @endif

                                        </td>

                                        <script>
                                            function swalConfirm(nomorKelompok, url) {
                                                Swal.fire({
                                                    title: 'Apakah Anda yakin?',
                                                    text: "Anda akan melakukan tindakan pada kelompok " + nomorKelompok + "?",
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
                        <p>Menampilkan {{ $rs_persetujuan_100->count() }} dari total
                            {{ $rs_persetujuan_100->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_persetujuan_100->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
