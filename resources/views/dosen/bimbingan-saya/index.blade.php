@extends('tim_capstone.base.app')

@section('title')
    Bimbingan Saya
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Bimbingan Saya</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Bimbingan Saya</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/dosen/bimbingan-saya/search') }}" method="get"
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
                                <th>Nomor Kelompok</th>
                                <th>Progress Kelompok</th>
                                <th>Posisi Pembimbing</th>
                                <th>Status Dosen</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_bimbingan_saya->count() > 0)
                                @foreach ($rs_bimbingan_saya as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_bimbingan_saya->firstItem() }}.</td>
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                        <td>{{ $kelompok->status_kelompok }}</td>
                                        <td>{{ $kelompok->jenis_dosen }}</td>
                                        <td>{{ $kelompok->status_dosen }}</td>

                                        <td class="text-center">
                                            @if ($kelompok->status_dosen == 'Persetujuan Dosbing Berhasil!')
                                                <a href="{{ url('/dosen/bimbingan-saya/tolak') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-danger btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/bimbingan-saya/tolak') }}/{{ $kelompok->id }}')">
                                                    Tolak</a>
                                            @elseif($kelompok->status_dosen == 'Persetujuan Dosbing Gagal!')
                                                <a href="{{ url('/dosen/bimbingan-saya/terima') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-primary btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/bimbingan-saya/terima') }}/{{ $kelompok->id }}')">
                                                    Terima</a>
                                            @else
                                                <a href="{{ url('/dosen/bimbingan-saya/terima') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-primary btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/bimbingan-saya/terima') }}/{{ $kelompok->id }}')">
                                                    Terima</a>
                                                <a href="{{ url('/dosen/bimbingan-saya/tolak') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-danger btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/bimbingan-saya/tolak') }}/{{ $kelompok->id }}')">
                                                    Tolak</a>
                                            @endif
                                            <a href="{{ url('/dosen/bimbingan-saya/detail') }}/{{ $kelompok->id }}"
                                                class="btn btn-outline-warning btn-xs m-1"> Detail</a>
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
