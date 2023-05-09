@extends('admin.base.app')

@section('title')
    Rekapitulasi Hasil Pekerjaan Seluruh Rumah Sakit
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan /</span> Rekapitulasi Hasil Pekerjaan Seluruh Rumah Sakit</h5>

                <div class="nav-align-top mb-2">
                    <ul class="nav nav-pills mb-3" role="tablist">
                      <li class="nav-item">
                        <a
                            href="{{url('/admin/holding-operasional/laporan/hasil-pekerjaan-rumah-sakit')}}"
                          type="button"
                          class="nav-link"
                        >
                          Perbaikan
                        </a>
                      </li>
                      <li class="nav-item">
                        <button
                           
                          type="button"
                          class="nav-link  active"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-pills-top-profile"
                          aria-controls="navs-pills-top-profile"
                          aria-selected="true"
                        >
                          Penggantian
                        </button>
                      </li>
                    </ul>
                </div>

                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">Rekapitulasi Hasil Pekerjaan Seluruh Rumah Sakit</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/holding-operasional/laporan/hasil-pekerjaan-rumah-sakit/penggantian/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-3 mt-2">
                                            <input class="form-control mr-sm-2" type="search" name="query_all" value="{{ !empty($query_all) ? $query_all : '' }}" placeholder="Cari ..." minlength="1">
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <!-- tahun -->
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

                        <br>

                        <h5>Penggantian</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Rumah Sakit</th>
                                        @foreach($rs_bulan as $key => $bulan)
                                        <th>{{$bulan}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_penggantian->count() > 0)
                                        @foreach($rs_penggantian as $index => $penggantian)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_penggantian->firstItem() }}</td>
                                            <td>{{ $penggantian->branch_name }}</td>
                                            @foreach($rs_bulan as $key => $bulan)
                                            <td class="text-center" @if(($penggantian->{'jumlah_total_'.$bulan}) > 0) data-bs-toggle="tooltip" data-bs-html="true" title="{!! ($penggantian->{'tooltip_title_'.$bulan}) !!}" @endif>
                                                {{--<strong>{{ round($penggantian->{$bulan},2) }}%</strong>--}}

                                                @if(($penggantian->{'jumlah_total_'.$bulan}) > 0)
                                                    <span class="badge bg-label-success">{{($penggantian->{'persen_selesai_'.$bulan})}}%</span>
                                                    <span class="badge bg-label-danger">{{($penggantian->{'persen_belum_dikerjakan_'.$bulan})}}%</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="14">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto ">
                                <p>Menampilkan {{ $rs_penggantian->count() }} dari total {{ $rs_penggantian->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_penggantian->links() }}
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

@endsection