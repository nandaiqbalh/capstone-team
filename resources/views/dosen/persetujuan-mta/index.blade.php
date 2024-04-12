@extends('tim_capstone.base.app')

@section('title')
    Persetujuan Dokumen Makalah TA
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Persetujuan Dokumen Makalah TA</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Persetujuan Dokumen Makalah TA</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/dosen/persetujuan-mta/search') }}" method="get"
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
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="reset">
                                        <i class="bx bx-reset"></i>
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
                                <th>Status Dokumen Makalah TA</th>
                                <th>Dokumen Makalah TA</th>
                                <th>Posisi Dosen</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_persetujuan_mta->count() > 0)
                                @foreach ($rs_persetujuan_mta as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_persetujuan_mta->firstItem() }}.</td>
                                        <td>{{ $kelompok->user_name }}</td>
                                        <td style="color: {{ $kelompok->status_dokumen_color }}">
                                            {{ $kelompok->file_status_mta }}</td>
                                        <td>
                                            @if ($kelompok->file_path_makalah && $kelompok->file_name_makalah)
                                                <a href="{{ asset($kelompok->file_path_makalah . '/' . $kelompok->file_name_makalah) }}"
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
                                                @if ($kelompok->file_status_mta_dosbing1 == 'Makalah TA Telah Disetujui!')
                                                    <a href="{{ url('/dosen/persetujuan-mta/tolak') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-danger btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-mta/tolak') }}/{{ $kelompok->id }}')">
                                                        Tolak</a>
                                                @elseif($kelompok->file_status_mta_dosbing1 == 'Menunggu Persetujuan Makalah TA!')
                                                    <a href="{{ url('/dosen/persetujuan-mta/terima') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-primary btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-mta/terima') }}/{{ $kelompok->id }}')">
                                                        Terima</a>
                                                    <a href="{{ url('/dosen/persetujuan-mta/tolak') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-danger btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-mta/tolak') }}/{{ $kelompok->id }}')">
                                                        Tolak</a>
                                                @elseif($kelompok->file_status_mta_dosbing1 == 'Makalah TA Tidak Disetujui Dosbing 1!')
                                                    <a href="{{ url('/dosen/persetujuan-mta/terima') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-primary btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-mta/terima') }}/{{ $kelompok->id }}')">
                                                        Terima</a>
                                                @else
                                                    <a href="{{ url('/dosen/kelompok-bimbingan/detail') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                                @endif
                                            @else
                                                @if ($kelompok->file_status_mta_dosbing2 == 'Makalah TA Telah Disetujui!')
                                                    <a href="{{ url('/dosen/persetujuan-mta/tolak') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-danger btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-mta/tolak') }}/{{ $kelompok->id }}')">
                                                        Tolak</a>
                                                @elseif($kelompok->file_status_mta_dosbing2 == 'Menunggu Persetujuan Makalah TA!')
                                                    <a href="{{ url('/dosen/persetujuan-mta/terima') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-primary btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-mta/terima') }}/{{ $kelompok->id }}')">
                                                        Terima</a>
                                                    <a href="{{ url('/dosen/persetujuan-mta/tolak') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-danger btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-mta/tolak') }}/{{ $kelompok->id }}')">
                                                        Tolak</a>
                                                @elseif($kelompok->file_status_mta_dosbing2 == 'Makalah TA Tidak Disetujui Dosbing 2!')
                                                    <a href="{{ url('/dosen/persetujuan-mta/terima') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-primary btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->user_name }}', '{{ url('/dosen/persetujuan-mta/terima') }}/{{ $kelompok->id }}')">
                                                        Terima</a>
                                                @else
                                                    <a href="{{ url('/dosen/kelompok-bimbingan/detail') }}/{{ $kelompok->id }}"
                                                        class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                                @endif
                                            @endif

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
                                                    confirmButtonText: 'Ya, Lanjutkan!',
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
                        <p>Menampilkan {{ $rs_persetujuan_mta->count() }} dari total
                            {{ $rs_persetujuan_mta->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_persetujuan_mta->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
