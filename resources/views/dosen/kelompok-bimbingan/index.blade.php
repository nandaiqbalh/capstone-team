@extends('tim_capstone.base.app')

@section('title')
    Kelompok Bimbingan
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Kelompok Bimbingan</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Kelompok Bimbingan</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/dosen/kelompok-bimbingan/search') }}" method="get"
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

                <div class="row">
                    <form action="{{ url('/dosen/kelompok-bimbingan/filter-status') }}" method="get" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-8"> <!-- Menyesuaikan dengan lebar yang diinginkan -->
                                <div class="mb-3">
                                    <select class="form-select" name="status" id="status" required>
                                        <option value="" disabled selected>-- Filter Status --</option>
                                        <option value="0">Belum Lulus</option>
                                        <option value="1">Sudah Lulus</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3"><!-- Menyesuaikan dengan lebar yang diinginkan -->
                                    <button type="submit" class="btn btn-primary float-end" name="action"
                                        value="search">Terapkan Filter</button>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>

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
                                                <a href="{{ url('/dosen/kelompok-bimbingan/tolak') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-danger btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/kelompok-bimbingan/tolak') }}/{{ $kelompok->id }}')">
                                                    Tolak</a>
                                            @elseif($kelompok->status_dosen == 'Persetujuan Dosbing Gagal!')
                                                <a href="{{ url('/dosen/kelompok-bimbingan/terima') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-primary btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/kelompok-bimbingan/terima') }}/{{ $kelompok->id }}')">
                                                    Terima</a>
                                            @elseif($kelompok->status_dosen == 'Menunggu Persetujuan Dosbing!')
                                                <a href="{{ url('/dosen/kelompok-bimbingan/terima') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-primary btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/kelompok-bimbingan/terima') }}/{{ $kelompok->id }}')">
                                                    Terima</a>
                                                <a href="{{ url('/dosen/kelompok-bimbingan/tolak') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-danger btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/kelompok-bimbingan/tolak') }}/{{ $kelompok->id }}')">
                                                    Tolak</a>
                                            @else
                                            @endif
                                            <a href="{{ url('/dosen/kelompok-bimbingan/detail') }}/{{ $kelompok->id }}"
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
