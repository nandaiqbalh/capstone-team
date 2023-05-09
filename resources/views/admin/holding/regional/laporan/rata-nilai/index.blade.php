@extends('admin.base.app')

@section('title')
    Rekapitulasi Rata-Rata Nilai Rumah Sakit Regional {{Auth::user()->region_id}}
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan /</span> Rekapitulasi Rata-Rata Nilai Rumah Sakit Regional {{Auth::user()->region_id}}</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">Rekapitulasi Rata-Rata Nilai Rumah Sakit Regional {{Auth::user()->region_id}}</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/holding-regional/laporan/rata-rata-nilai/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-3 mt-2">
                                            <input class="form-control mr-sm-2" type="search" name="query_all" value="{{ !empty($query_all) ? $query_all : '' }}" placeholder="Cari ..." minlength="3">
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <!-- bulan -->
                                            <select class="form-control form-select mr-sm-2 select-2" name="year" required>
                                                <option value="" disabled selected>Pilih Tahun</option>
                                                @foreach($rs_year as $index => $yeardb)
                                                <option value="{{$yeardb->name}}" @if( !empty($year) && $year == $yeardb->name ) selected @endif>{{$yeardb->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-auto mt-1">
                                            <button class="btn btn-outline-secondary ml-1" type="submit" name="action" value="search">
                                                <i class="bx bx-search-alt-2"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary ml-1" type="submit" name="action" value="reset">
                                                <i class="bx bx-reset"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row justify-content-end mb-2">
                            <div class="col-auto ">
                                
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Rumah Sakit</th>
                                        <th width="5%">Target</th>
                                        @foreach($rs_bulan as $key => $bulan)
                                        <th width="5%">{{$bulan}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_rata_nilai->count() > 0)
                                        @foreach($rs_rata_nilai as $index => $rata_nilai)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $rata_nilai->branch_name }}</td>
                                            <td class="text-center">{{$rata_nilai->target_nilai}}%</td>
                                            @foreach($rs_bulan as $key => $bulan)
                                            <td class="text-center">{{ round($rata_nilai->{$bulan},2) }}%</td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="13">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto ">
                                <p>Menampilkan {{ $rs_rata_nilai->count() }} dari total {{ $rs_rata_nilai->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_rata_nilai->links() }}
                            </div>
                        </div>
                        <br>

                        <br>
                        <h5>Rekapitulasi Nilai Rata - Rata Seluruh Rumah Sakit</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="3"></th>
                                        @foreach($rs_bulan as $key => $bulan)
                                        <th width="5%">{{$bulan}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_rata_nilai->count() > 0)
                                        <tr>
                                            <td>
                                                <strong>Jumlah Total</strong>
                                            </td>
                                            @foreach($rs_bulan as $key => $bulan)
                                            <td class="text-center">{{ round($rs_total_rata_nilai[$bulan],2) }}%</td>
                                            @endforeach
                                        </tr>
                                    @else
                                        <tr class="text-center">
                                            <td colspan="13">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
    
@endsection