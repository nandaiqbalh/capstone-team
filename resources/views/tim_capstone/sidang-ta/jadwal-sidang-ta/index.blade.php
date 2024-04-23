@extends('tim_capstone.base.app')

@section('title')
    Jadwal Sidang Tugas Akhir
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Jadwal Sidang Tugas Akhir</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Jadwal Sidang Tugas Akhir</h5>

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nomor Kelompok </th>
                                <th>Nama Mahasiswa</th>
                                <th>Status Sidang</th>
                                <th>Status Laporan TA</th>
                                <th>Periode Sidang</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Ruangan</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_sidang->count() > 0)
                                @foreach ($rs_sidang as $index => $sidang_ta)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_sidang->firstItem() }}.</td>
                                        <td>{{ $sidang_ta->nomor_kelompok }}</td>
                                        <td>{{ $sidang_ta->user_name }}</td>
                                        <td style="color: {{ $sidang_ta->status_sidang_color }}">
                                            {{ $sidang_ta->status_tugas_akhir }}</td>
                                        <td style="color: {{ $sidang_ta->status_lta_color }}">
                                            {{ $sidang_ta->file_status_lta }}</td <td>
                                        <td>{{ $sidang_ta->nama_periode }}</td>
                                        <td>{{ $sidang_ta->hari_sidang }}, {{ $sidang_ta->tanggal_sidang }}</td>
                                        <td>{{ $sidang_ta->waktu_sidang }} WIB - {{ $sidang_ta->waktu_selesai }}
                                            WIB
                                        <td>{{ $sidang_ta->nama_ruang }}</td>
                                        <td class="text-center">

                                            @if ($sidang_ta->status_tugas_akhir == 'Lulus Sidang TA!')
                                                <a href="{{ url('/admin/jadwal-sidang-ta/to-gagal') }}/{{ $sidang_ta->id_mahasiswa }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda yakin kelompok {{ $sidang_ta->user_name }} tidak lulus?')">
                                                    Gagal</a>
                                            @elseif($sidang_ta->status_tugas_akhir == 'Gagal Sidang TA!')
                                                <a href="{{ url('/admin/jadwal-sidang-ta/to-lulus') }}/{{ $sidang_ta->id_mahasiswa }}"
                                                    class="btn btn-outline-primary btn-xs m-1">Lulus</a>
                                            @else
                                                <a href="{{ url('/admin/jadwal-sidang-ta/to-gagal') }}/{{ $sidang_ta->id_mahasiswa }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda yakin kelompok {{ $sidang_ta->user_name }} tidak lulus?')">
                                                    Gagal</a>
                                                <a href="{{ url('/admin/jadwal-sidang-ta/to-lulus') }}/{{ $sidang_ta->id_mahasiswa }}"
                                                    class="btn btn-outline-primary btn-xs m-1">Lulus</a>
                                            @endif
                                            <a href="{{ url('/admin/jadwal-sidang-ta/detail') }}/{{ $sidang_ta->id_mahasiswa }}"
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
