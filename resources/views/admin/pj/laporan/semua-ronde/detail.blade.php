@extends('admin.base.app')
@inject('dtid','App\Helpers\DateIndonesia')

@section('title')
    Ronde
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ronde</span></h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">{{$laporan_ronde->round_name}} Bulan {{$dtid->get_month_year($laporan_ronde->created_date)}}</h5><br>
                        {{-- <small class="form-text text-muted">{{ucwords($round->status)}}</small></h5> --}}

                    <div class="card-body">    
                        {{-- <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/checker/ronde/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-auto mt-1">
                                            <input class="form-control mr-sm-2" type="search" name="search" value="{{ !empty($search) ? $search : '' }}" placeholder="Cari ..." minlength="1" required>
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
                        <br> --}}

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <td></td>
                                        <th>Total Komponen Penilaian</th>
                                        <th>Jumlah Komponen Pembersihan</th>
                                        <th>% Pembersihan</th>
                                        <th>Jumlah Komponen Perbaikan</th>
                                        <th>% Perbaikan</th>
                                        <th>Jumlah Komponen Penggantian</th>
                                        <th>% Penggantian</th>
                                        <th>Bobot Penilaian ABRT-RL</th>
                                    </tr>
                                </thead>
                                <tbody>     
                                    
                                    <tr>
                                        <td>Total</td>
                                        <td>{{$summary['total_komponen']}}</td>
                                        <td>{{$summary['total_pembersihan']}}</td>
                                        <td>{{round($summary['persen_pembersihan'],2)}}%</td>
                                        <td>{{$summary['total_perbaikan']}}</td>
                                        <td>{{round($summary['persen_perbaikan'],2)}}%</td>
                                        <td>{{$summary['total_penggantian']}}</td>
                                        <td>{{round($summary['persen_penggantian'],2)}}%</td>
                                        <td>{{round($bobot_penilaian['persen_abrt_rl'],2)}}%</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
        
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr style="vertical-align: middle;">
                                        <th width="20%">Aman</th>
                                        <th width="20%">Bersih</th>
                                        <th width="20%">Rapih</th>
                                        <th width="20%">Tampak Baru</th>
                                        <th width="20%">Ramah Lingkungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <tr>
                                        <td>{{round($akumulasi_parameter['persen_aman'],2)}}%</td>
                                        <td>{{round($akumulasi_parameter['persen_bersih'],2)}}%</td>
                                        <td>{{round($akumulasi_parameter['persen_rapih'],2)}}%</td>
                                        <td>{{round($akumulasi_parameter['persen_tampak_baru'],2)}}%</td>
                                        <td>{{round($akumulasi_parameter['persen_ramah_lingkungan'],2)}}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Lokasi</th>
                                        <th>Area</th>
                                        <th>Sub Area</th>
                                        <th>Item Penilaian</th>
                                        <th>Komponen Penilaian</th>
                                        <th>Nilai</th>
                                        <th width="18%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @if($rs_assessment->count() > 0)
                                            @foreach($rs_assessment as $index => $assessment)
                                            <tr>
                                                <td class="text-center">{{ $index + $rs_assessment->firstItem() }}.</td>
                                                <td>{{ $assessment->lokasi }}</td>
                                                <td>{{ $assessment->area }}</td>
                                                <td>{{ $assessment->sub_area }}</td>
                                                <td>{{ $assessment->item }} #{{ $assessment->unique_id }}</td>
                                                <td>{{ $assessment->komponen }}</td>
                                                <td>{{ $assessment->nilai }}</td>
                                                <td class="text-center">
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-outline-warning btn-xs m-1 " data-bs-toggle="modal" data-bs-target="#modal{{$index}}">
                                                    Detail
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modal{{$index}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body" id="qrCode">
                                                                <div class="text-center">
                                                                    <img style="max-height: 260px" src="{{$api_img}}/{{ $assessment->nama_gambar }}" class="img-thumbnail mx-auto d-block" alt="{{ $assessment->nama_gambar }}">
                                                                </div>
                                                                <div class="mt-4">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-borderless table-hover ">
                                                                            
                                                                            <tbody class="align-baseline" style="text-align: left;">
                                                                                <tr>
                                                                                    <td width="25%">Nilai</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assessment->nilai }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Parameter</td>
                                                                                    <td>:</td>
                                                                                    <td>{{ $assessment->parameter }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Keterangan</td>
                                                                                    <td>:</td>
                                                                                    <td>{{ $assessment->keterangan }}</td>
                                                                                </tr>
                                                                                
                                                                                
                                                                                
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                            {{-- <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                                                            </div> --}}
                                                        </div>
                                                        </div>
                                                    </div>

                                                </td>
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
                                <p>Menampilkan {{ $rs_assessment->count() }} dari total {{ $rs_assessment->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_assessment->links() }}
                            </div>
                        </div>
                        {{-- tombol submit ronde --}}
                       
                    </div>
                </div>
            </div>

@endsection