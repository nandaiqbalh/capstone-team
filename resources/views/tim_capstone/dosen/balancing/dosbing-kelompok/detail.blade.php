@extends('tim_capstone.base.app')

@section('title')
    Balancing Dosbing Kelompok
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Kelompok Bimbingan Dosen Pembimbing
        </h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Kelompok Bimbingan Dosen Pembimbing</h5>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nomor Kelompok</th>
                                <th>Status Kelompok</th>
                                <th>Posisi Dosen</th>
                                <th>Selesai</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_bimbingan->count() > 0)
                                @foreach ($rs_bimbingan as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_bimbingan->firstItem() }}.</td>
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                        <td>{{ $kelompok->status_kelompok }}</td>
                                        <td>{{ $kelompok->jenis_dosen }}</td>
                                        <td>
                                            @if ($kelompok->is_selesai == 0)
                                                <span style="color: #F86F03;">
                                                    Belum Selesai
                                                </span>
                                            @else
                                                <span style="color: #44B158;">
                                                    Selesai
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ url('/admin/kelompok-valid/detail') }}/{{ $kelompok->id }}"
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
                        <p>Menampilkan {{ $rs_bimbingan->count() }} dari total {{ $rs_bimbingan->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_bimbingan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
