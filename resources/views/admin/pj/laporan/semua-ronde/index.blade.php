@extends('admin.base.app')

@section('title')
    Semua Ronde
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan /</span>Semua Ronde</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">Semua Ronde</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/checker/laporan/semua-ronde/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-4 mt-2">
                                            <input class="form-control mr-sm-2" type="search" name="query_all" value="{{ !empty($query_all) ? $query_all : '' }}" placeholder="Cari ..." minlength="1">
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <!-- bulan -->
                                            <select class="form-control form-select mr-sm-2" name="month" required>
                                                <option value="" disabled selected>Pilih Bulan</option>
                                                <option value="01" @if( !empty($month) && $month == '01' ) selected @endif>Januari</option>
                                                <option value="02" @if( !empty($month) && $month == '02') selected @endif>Februari</option>
                                                <option value="03" @if( !empty($month) && $month == '03' ) selected @endif>Maret</option>
                                                <option value="04" @if( !empty($month) && $month == '04' ) selected @endif>April</option>
                                                <option value="05" @if( !empty($month) && $month == '05' ) selected @endif>Mei</option>
                                                <option value="06" @if( !empty($month) && $month == '06' ) selected @endif>Juni</option>
                                                <option value="07" @if( !empty($month) && $month == '07' ) selected @endif>Juli</option>
                                                <option value="08" @if( !empty($month) && $month == '08' ) selected @endif>Agustus</option>
                                                <option value="09" @if( !empty($month) && $month == '09' ) selected @endif>September</option>
                                                <option value="10" @if( !empty($month) && $month == '10' ) selected @endif>Oktober</option>
                                                <option value="11" @if( !empty($month) && $month == '11' ) selected @endif>November</option>
                                                <option value="12" @if( !empty($month) && $month == '12' ) selected @endif>Desember</option>
                                            </select>
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
                                        <div class="col-auto mt-2">
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
                                        {{-- <th>Regional</th>
                                        <th>Bamasama</th> --}}
                                        <th>Ronde</th>
                                        <th width="10%">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_laporan_ronde->count() > 0)
                                        @foreach($rs_laporan_ronde as $index => $laporan_ronde)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_laporan_ronde->firstItem() }}</td>
                                            {{-- <td>{{ $laporan_ronde->region_name }}</td>
                                            <td>{{ $laporan_ronde->branch_name }}</td> --}}
                                            <td>{{ $laporan_ronde->round_name }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/admin/checker/laporan/semua-ronde/detail') }}/{{ $laporan_ronde->id }}" class="btn btn-outline-secondary btn-xs m-1 ">Detail</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="5">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto ">
                                <p>Menampilkan {{ $rs_laporan_ronde->count() }} dari total {{ $rs_laporan_ronde->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_laporan_ronde->links() }}
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
    
@endsection