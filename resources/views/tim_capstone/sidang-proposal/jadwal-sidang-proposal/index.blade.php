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
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/jadwal-sidang-proposal/search') }}" method="get"
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
                <div class="row">
                    <form action="{{ url('/admin/jadwal-sidang-proposal/filter-siklus') }}" method="get"
                        autocomplete="off">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-8"> <!-- Menyesuaikan dengan lebar yang diinginkan -->
                                <div class="mb-3">
                                    <select class="form-select select-2" name="id_siklus" required>
                                        <option value="" disabled selected>-- Filter Berdasarkan Siklus --
                                        </option>
                                        @foreach ($rs_siklus as $siklus)
                                            <option value="{{ $siklus->id }}">{{ $siklus->nama_siklus }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4"> <!-- Menyesuaikan dengan lebar yang diinginkan -->
                                <button type="submit" class="btn btn-primary float-end" name="action"
                                    value="filter">Terapkan
                                    Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nomor Kelompok</th>
                                <th>Status Sidang</th>
                                <th>Status C100</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Ruangan</th>
                                <th>Penguji 1</th>
                                <th>Penguji 2</th>
                                <th>Pembimbing 2</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_sidang->count() > 0)
                                @foreach ($rs_sidang as $index => $sidang_proposal)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_sidang->firstItem() }}.</td>
                                        <td>{{ $sidang_proposal->nomor_kelompok }}</td>
                                        <td style="color: {{ $sidang_proposal->status_sidang_color }}">
                                            {{ $sidang_proposal->status_sidang_proposal }}</td>
                                        <td style="color: {{ $sidang_proposal->status_c100_color }}">
                                            {{ $sidang_proposal->file_status_c100 }}</td>
                                        <td>{{ $sidang_proposal->hari_sidang }}, {{ $sidang_proposal->tanggal_sidang }}
                                        </td>
                                        <td>{{ $sidang_proposal->waktu_sidang }} WIB -
                                            {{ $sidang_proposal->waktu_selesai }}
                                            WIB
                                        <td>{{ $sidang_proposal->nama_ruang }}</td>
                                        <td>{{ $sidang_proposal->nama_dosen_penguji_1 }}</td>
                                        <td>{{ $sidang_proposal->nama_dosen_penguji_2 }}</td>
                                        <td>{{ $sidang_proposal->nama_dosen_pembimbing_2 }}</td>

                                        <td class="text-center">

                                            @if ($sidang_proposal->status_sidang_proposal == 'Lulus Sidang Proposal!')
                                                <a href="{{ url('/admin/jadwal-sidang-proposal/to-gagal') }}/{{ $sidang_proposal->id_kelompok }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda yakin kelompok {{ $sidang_proposal->nomor_kelompok }} tidak lulus?')">
                                                    Gagal</a>
                                            @elseif($sidang_proposal->status_sidang_proposal == 'Gagal Sidang Proposal!')
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
                                            <a href="{{ url('/admin/jadwal-sidang-proposal/detail') }}/{{ $sidang_proposal->id_kelompok }}"
                                                class="btn btn-outline-secondary btn-xs m-1">Detail</a>

                                        </td>
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
