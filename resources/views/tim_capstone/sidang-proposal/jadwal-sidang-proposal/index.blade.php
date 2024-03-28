@extends('tim_capstone.base.app')

@section('title')
    Jadwal Sidang Proposal
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Jadwal Sidang Proposal</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Jadwal Sidang Proposal</h5>

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Siklus</th>
                                <th>Nomor Kelompok</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Ruangan</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_sidang->count() > 0)
                                @foreach ($rs_sidang as $index => $pendaftaran)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_sidang->firstItem() }}.</td>
                                        <td>{{ $pendaftaran->tahun_ajaran }}</td>
                                        <td>{{ $pendaftaran->nomor_kelompok }}</td>
                                        <td>{{ $pendaftaran->hari_sidang }}, {{ $pendaftaran->tanggal_sidang }}</td>
                                        <td>{{ $pendaftaran->waktu_sidang }} WIB - {{ $pendaftaran->waktu_selesai }} WIB
                                        <td>{{ $pendaftaran->nama_ruang }}</td>

                                        <td class="text-center">
                                            <a href="{{ url('/admin/penjadwalan-sidang-proposal/jadwalkan-sidang-proposal') }}/{{ $pendaftaran->id_kelompok }}"
                                                class="btn btn-outline-warning btn-xs m-1">Ubah</a>
                                            <a href="{{ url('/admin/jadwal-sidang-proposal/delete-process') }}/{{ $pendaftaran->id }}"
                                                class="btn btn-outline-danger btn-xs m-1 "
                                                onclick="return confirm('Apakah anda ingin menghapus {{ $pendaftaran->nomor_kelompok }} ?')">
                                                Hapus</a>
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
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto mr-auto">
                        <p>Menampilkan {{ $rs_sidang->count() }} dari total {{ $rs_sidang->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_sidang->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
