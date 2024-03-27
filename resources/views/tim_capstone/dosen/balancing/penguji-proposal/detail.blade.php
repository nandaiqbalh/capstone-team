@extends('tim_capstone.base.app')

@section('title')
    Pengujian Dosen Penguji Proposal
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Pengujian Dosen Penguji Proposal</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Pengujian Dosen Penguji Proposal</h5>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nomor Kelompok</th>
                                <th>Dosen</th>
                                <th>Status Dosen</th>
                                <th>Status Sidang</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_penguji_proposal->count() > 0)
                                @foreach ($rs_penguji_proposal as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_penguji_proposal->firstItem() }}.</td>
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                        <td>{{ $kelompok->jenis_dosen }}</td>
                                        <td>{{ $kelompok->status_dosen }}</td>
                                        <td>
                                            @if ($kelompok->is_selesai == 0)
                                                <a href="#" class="btn btn-outline-secondary btn-xs m-1 ">Belum
                                                    Sidang</a>
                                            @else
                                                <a href="#" class="btn btn-outline-danger btn-xs m-1">Sudah
                                                    Sidang</a>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ url('/admin/kelompok-valid/detail') }}/{{ $kelompok->id }}"
                                                class="btn btn-outline-warning btn-xs m-1"> Detail</a>
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
                        <p>Menampilkan {{ $rs_penguji_proposal->count() }} dari total {{ $rs_penguji_proposal->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_penguji_proposal->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
