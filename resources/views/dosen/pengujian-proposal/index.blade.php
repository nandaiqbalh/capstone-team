@extends('tim_capstone.base.app')

@section('title')
    Pengujian Proposal
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Pengujian Proposal</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Pengujian Proposal</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/dosen/pengujian-proposal/search') }}" method="get"
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
                                <th>Posisi Dosen</th>
                                <th>Status Saya</th>
                                <th>Hari, tanggal</th>
                                <th>Waktu</th>
                                <th>Tempat</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_pengujian_proposal->count() > 0)
                                @foreach ($rs_pengujian_proposal as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_pengujian_proposal->firstItem() }}.</td>
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                        <td>{{ $kelompok->jenis_dosen }}</td>
                                        @if ($kelompok->jenis_dosen == 'Penguji 1')
                                            <td style="color: {{ $kelompok->status_penguji1_color }}">
                                                {{ $kelompok->status_dosen }}
                                            </td>
                                        @elseif($kelompok->jenis_dosen == 'Penguji 2')
                                            <td style="color: {{ $kelompok->status_penguji2_color }}">
                                                {{ $kelompok->status_dosen }}
                                            </td>
                                        @elseif($kelompok->jenis_dosen == 'Pembimbing 2')
                                            <td style="color: {{ $kelompok->status_pembimbing2_color }}">
                                                {{ $kelompok->status_dosen }}
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ $kelompok->hari_sidang }}, {{ $kelompok->tanggal_sidang }}</td>
                                        <td>{{ $kelompok->waktu_sidang }} WIB - {{ $kelompok->waktu_selesai }} WIB
                                        <td>{{ $kelompok->nama_ruang }}</td>

                                        <td class="text-center">

                                            @if ($kelompok->is_sidang_proposal == 1)
                                                <a href="{{ url('/dosen/pengujian-proposal/detail') }}/{{ $kelompok->id_kelompok }}"
                                                    class="btn btn-outline-secondary btn-xs m-1"> Detail</a>

                                            @else
                                                @if ($kelompok->status_dosen == 'Penguji Setuju!' || $kelompok->status_dosen == 'Pembimbing Setuju!')
                                                    <a href="{{ url('/dosen/pengujian-proposal/tolak') }}/{{ $kelompok->id_kelompok }}"
                                                        class="btn btn-outline-danger btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/pengujian-proposal/tolak') }}/{{ $kelompok->id_kelompok }}')">
                                                        Tolak</a>
                                                @elseif(
                                                    $kelompok->status_dosen == 'Menunggu Persetujuan Penguji!' ||
                                                        $kelompok->status_dosen == 'Menunggu Persetujuan Pembimbing!')
                                                    <a href="{{ url('/dosen/pengujian-proposal/terima') }}/{{ $kelompok->id_kelompok }}"
                                                        class="btn btn-outline-success btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/pengujian-proposal/terima') }}/{{ $kelompok->id_kelompok }}')">
                                                        Terima</a>
                                                    <a href="{{ url('/dosen/pengujian-proposal/tolak') }}/{{ $kelompok->id_kelompok }}"
                                                        class="btn btn-outline-danger btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/pengujian-proposal/tolak') }}/{{ $kelompok->id_kelompok }}')">
                                                        Tolak</a>
                                                @elseif($kelompok->status_dosen == 'Penguji Tidak Setuju!' || $kelompok->status_dosen == 'Pembimbing Tidak Setuju!')
                                                    <a href="{{ url('/dosen/pengujian-proposal/terima') }}/{{ $kelompok->id_kelompok }}"
                                                        class="btn btn-outline-success btn-xs m-1"
                                                        onclick="event.preventDefault(); swalConfirm('{{ $kelompok->nomor_kelompok }}', '{{ url('/dosen/pengujian-proposal/terima') }}/{{ $kelompok->id_kelompok }}')">
                                                        Terima</a>
                                                @else
                                                @endif
                                                <a href="{{ url('/dosen/pengujian-proposal/detail') }}/{{ $kelompok->id_kelompok }}"
                                                    class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
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
                        <p>Menampilkan {{ $rs_pengujian_proposal->count() }} dari total
                            {{ $rs_pengujian_proposal->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_pengujian_proposal->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
