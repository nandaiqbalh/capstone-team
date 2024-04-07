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
                                <th>Status Kelompok</th>
                                <th>Hasil</th>
                                <th>Tindakan</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_sidang->count() > 0)
                                @foreach ($rs_sidang as $index => $sidang_proposal)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_sidang->firstItem() }}.</td>
                                        <td>{{ $sidang_proposal->nama_siklus }}</td>
                                        <td>{{ $sidang_proposal->nomor_kelompok }}</td>
                                        <td>{{ $sidang_proposal->hari_sidang }}, {{ $sidang_proposal->tanggal_sidang }}</td>
                                        <td>{{ $sidang_proposal->waktu_sidang }} WIB - {{ $sidang_proposal->waktu_selesai }}
                                            WIB
                                        <td>{{ $sidang_proposal->nama_ruang }}</td>
                                        <td>{{ $sidang_proposal->status_kelompok }}</td>
                                        <td class="text-center">

                                            @if ($sidang_proposal->status_kelompok == 'Lulus Sidang Proposal!')
                                                <a href="{{ url('/admin/jadwal-sidang-proposal/to-gagal') }}/{{ $sidang_proposal->id_kelompok }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda yakin kelompok {{ $sidang_proposal->nomor_kelompok }} tidak lulus?')">
                                                    Gagal</a>
                                            @elseif($sidang_proposal->status_kelompok == 'Gagal Sidang Proposal!')
                                                <a href="{{ url('/admin/jadwal-sidang-proposal/to-lulus') }}/{{ $sidang_proposal->id_kelompok }}"
                                                    class="btn btn-outline-primary btn-xs m-1">Lulus</a>
                                            @else
                                                <a href="{{ url('/admin/jadwal-sidang-proposal/to-gagal') }}/{{ $sidang_proposal->id_kelompok }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda yakin kelompok {{ $sidang_proposal->nomor_kelompok }} tidak lulus?')">
                                                    Gagal</a>
                                                <a href="{{ url('/admin/jadwal-sidang-proposal/to-lulus') }}/{{ $sidang_proposal->id_kelompok }}"
                                                    class="btn btn-outline-primary btn-xs m-1">Lulus</a>
                                            @endif

                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('/admin/penjadwalan-sidang-proposal/jadwalkan-sidang-proposal') }}/{{ $sidang_proposal->id_kelompok }}"
                                                class="btn btn-outline-warning btn-xs m-1">Ubah</a>
                                            <a href="{{ url('/admin/jadwal-sidang-proposal/delete-process') }}/{{ $sidang_proposal->id }}"
                                                class="btn btn-outline-danger btn-xs m-1 "
                                                onclick="return confirm('Apakah anda ingin menghapus {{ $sidang_proposal->nomor_kelompok }} ?')">
                                                Hapus</a>

                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="7">Tidak ada data.</td>
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
