@extends('admin.base.app')

@section('title')
Rekapitulasi Pekerjaan Terlambat Persetujuan
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan /</span> Rekapitulasi Pekerjaan Terlambat Persetujuan</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Rekapitulasi Pekerjaan Terlambat Persetujuan ({{$round->name}} Bulan {{$bulan}})</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/holding-operasional/laporan/pekerjaan-terlambat-persetujuan') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Rumah Sakit</th>
                                        <th width="15%">Kelas</th>
                                        <th width="15%">Regional</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_terlambat_persetujuan->count() > 0)
                                        @foreach($rs_terlambat_persetujuan as $index => $terlambat_persetujuan)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $terlambat_persetujuan->name }}</td>
                                            <td class="text-center">{{ $terlambat_persetujuan->class }}</td>
                                            <td>{{ $terlambat_persetujuan->region_name }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="4">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
@endsection