@extends('tim_capstone.base.app')

@section('title')
    Penjadwalan Sidang Proposal
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Penjadwalan Sidang Proposal</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Kelompok</h5>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/penjadwalan-sidang-proposal/search') }}"
                            method="get" autocomplete="off">
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
                    <form action="{{ url('/admin/penjadwalan-sidang-proposal/filter-siklus') }}" method="get"
                        autocomplete="off">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-8"> <!-- Menyesuaikan dengan lebar yang diinginkan -->
                                <div class="mb-3">
                                    <select class="form-select select-2" name="id_siklus" required>
                                        <option value="" disabled selected> -- Filter Berdasarkan Siklus -- </option>
                                        @foreach ($rs_siklus as $s)
                                            <option value="{{ $s->id }}"
                                                {{ isset($siklus) && $siklus->id == $s->id ? 'selected' : '' }}>
                                                {{ $s->nama_siklus }}
                                            </option>
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
                                <th>Status Dokumen C100</th>
                                <th>Siklus Pendaftaran</th>
                                <th>Status Penguji 1</th>
                                <th>Status Penguji 2</th>
                                <th>Status Pembimbing 2</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_kelompok->count() > 0)
                                @foreach ($rs_kelompok as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_kelompok->firstItem() }}.</td>
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                        <td style="color: {{ $kelompok->status_sidang_color }}">
                                            {{ $kelompok->status_sidang_proposal }}</td>
                                        <td style="color: {{ $kelompok->status_dokumen_color }}">
                                            {{ $kelompok->file_status_c100 }}</td>
                                        <td>{{ $kelompok->nama_siklus }}</td>
                                        <td style="color: {{ $kelompok->status_penguji1_color }}">
                                            {{ $kelompok->status_dosen_penguji_1 ?? '-' }}
                                        </td>
                                        <td style="color: {{ $kelompok->status_penguji2_color }}">
                                            {{ $kelompok->status_dosen_penguji_2 ?? '-' }}
                                        </td>
                                        <td style="color: {{ $kelompok->status_pembimbing2_color }}">
                                            {{ $kelompok->status_dosen_pembimbing_2 ?? '-' }}
                                        </td>

                                        @if (
                                            $kelompok->status_sidang_proposal == 'Menunggu Dijadwalkan Sidang!' ||
                                                $kelompok->status_sidang_proposal == 'Penguji Proposal Ditetapkan!')
                                            <td class="text-center">
                                                <a href="{{ url('/admin/penjadwalan-sidang-proposal/jadwalkan-sidang-proposal') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-primary btn-xs m-1 ">Jadwalkan Sidang</a>
                                            </td>
                                        @elseif($kelompok->status_sidang_proposal == 'Dijadwalkan Sidang Proposal!')
                                            <td class="text-center">
                                                <a href="{{ url('/admin/jadwal-sidang-proposal/detail') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-secondary btn-xs m-1 ">Detail</a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <a href="{{ url('/admin/penjadwalan-sidang-proposal/jadwalkan-sidang-proposal') }}/{{ $kelompok->id }}"
                                                    class="btn btn-outline-warning btn-xs m-1 ">Ubah</a>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="9">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto mr-auto">
                        <p>Menampilkan {{ $rs_kelompok->count() }} dari total {{ $rs_kelompok->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_kelompok->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
