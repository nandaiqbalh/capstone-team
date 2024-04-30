@extends('tim_capstone.base.app')

@section('title')
    Pengujian Dosen Penguji Sidang TA
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Pengujian Dosen Penguji Sidang TA</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Pengujian Dosen Penguji Sidang TA</h5>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Nomor Kelompok</th>
                                <th>Dosen</th>
                                <th>Status Dosen</th>
                                <th>Status Sidang</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_penguji_ta->count() > 0)
                                @foreach ($rs_penguji_ta as $index => $mahasiswa)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_penguji_ta->firstItem() }}.</td>
                                        <td>{{ $mahasiswa->user_name }}</td>
                                        <td>{{ $mahasiswa->nomor_kelompok }}</td>
                                        <td>{{ $mahasiswa->jenis_dosen }}</td>
                                        <td>{{ $mahasiswa->status_dosen }}</td>
                                        @if ($mahasiswa->is_selesai == 0)
                                            <td style="color: #F86F03">Belum
                                                Sidang</td>
                                        @else
                                            <td style="color: #44B158">Sudah
                                                Sidang</td>
                                        @endif

                                        <td class="text-center">
                                            <a href="{{ url('/tim-capstone/balancing-penguji-ta/detail-mahasiwa') }}/{{ $mahasiswa->id_mahasiswa }}"
                                                class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                        </td>

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
                        <p>Menampilkan {{ $rs_penguji_ta->count() }} dari total {{ $rs_penguji_ta->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_penguji_ta->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
