@extends('tim_capstone.base.app')

@section('title')
    Penetapan Anggota Kelompok
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Penetapan Kelompok</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Pendaftaran</h5>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form class="form-inline" action="{{ url('/tim-capstone/penetapan-anggota/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="nama"
                                        value="{{ !empty($nama) ? $nama : '' }}" placeholder="Nama" minlength="1" required>
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
                    <div class="col-md-6">
                        <form class="form-inline" action="{{ url('/tim-capstone/penetapan-anggota/add') }}" method="get"
                            autocomplete="off">
                            <div class="row float-end">
                                <div class="col-auto mt-1">
                                    <select class="form-select" name="id_topik" required>
                                        <option value="" disabled selected>-- Pilih Topik--</option>
                                        @foreach ($rs_topik as $topik)
                                            <option value="{{ $topik->id }}"
                                                @if (old('id_topik') == '{{ $topik->id }}') selected @endif>{{ $topik->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto mt-1">
                                    <button class="btn btn-info ml-1" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="Tambah Kelompok" type="submit">
                                        <i class="bx bx-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <br>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Topik</th>
                                <th>Peminatan</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_pendaftaran->count() > 0)
                                @foreach ($rs_pendaftaran as $index => $pendaftaran)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_pendaftaran->firstItem() }}.</td>
                                        <td>{{ $pendaftaran->user_name }}</td>
                                        <td>{{ $pendaftaran->prioritas_topik }}</td>
                                        <td>{{ $pendaftaran->prioritas_peminatan }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/tim-capstone/mahasiswa/detail') }}/{{ $pendaftaran->user_id }}"
                                                class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
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
                        <p>Menampilkan {{ $rs_pendaftaran->count() }} dari total {{ $rs_pendaftaran->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_pendaftaran->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
