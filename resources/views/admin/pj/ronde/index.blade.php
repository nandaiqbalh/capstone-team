@extends('admin.base.app')
@inject('dtid','App\Helpers\DateIndonesia')

@section('title')
    Rekapitulasi Penilaian
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Rekapitulasi Penilaian</span></h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">{{$round->nama_ronde}} Bulan {{$dtid->get_month_year(date('Y-m-d'))}} ({{$rs_assessment_count}}/{{$rs_assessment_all}})<br> 
                        <small class="form-text text-muted">{{ucwords($round->status)}}</small></h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/checker/ronde/penilaian/search') }}" method="get" autocomplete="off">
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
                                        <th>Revisi</th>
                                        <th width="18%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @if($rs_assessment->count() > 0)
                                            @foreach($rs_assessment as $index => $assessment)
                                            <tr>
                                                <td class="text-center">{{ $index + $rs_assessment->firstItem() }}.</td>
                                                <td>{{ $assessment->nama_lokasi }}</td>
                                                <td>{{ $assessment->nama_area }}</td>
                                                <td>{{ $assessment->nama_sub_area }}</td>
                                                <td>{{ $assessment->nama_item }} #{{ $assessment->unique_id }}</td>
                                                <td>{{ $assessment->nama_komponen }}</td>
                                                @if ($assessment->nilai)
                                                <td>{{ $assessment->nilai }}</td>
                                                @else
                                                <td>-</td>
                                                @endif
                                                <td>{{ $assessment->status_revisi }}</td>
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
                                                                                    <td width="35%">Area</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assessment->nama_area }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Sub Area</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assessment->nama_sub_area }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Item Penilaian</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assessment->nama_item }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Komponen Penilaian</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assessment->nama_komponen }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Nilai</td>
                                                                                    <td width="5%">:</td>
                                                                                    @if ($assessment->nilai)
                                                                                    <td>{{ $assessment->nilai }}</td>
                                                                                    @else
                                                                                    <td>-</td>
                                                                                    @endif
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td>Parameter</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assessment->parameter }}</td> --}}
                                                                                    @if ($assessment->parameter)
                                                                                    <td>{{ $assessment->parameter }}</td>
                                                                                    @else
                                                                                    <td>-</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Keterangan Penilaian</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assessment->keterangan }}</td> --}}
                                                                                    @if ($assessment->keterangan)
                                                                                    <td>{{ $assessment->keterangan }}</td>
                                                                                    @else
                                                                                    <td>-</td>
                                                                                    @endif
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    @if ($assessment->revisi_deskripsi)
                                                                                    <td>Keterangan Revisi Penilaian</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assessment->revisi_deskripsi }}</td> --}}
                                                                                    @if ($assessment->revisi_deskripsi)
                                                                                    <td>{{ $assessment->revisi_deskripsi }}</td>
                                                                                    @else
                                                                                    <td>-</td>
                                                                                    @endif
                                                                                    @endif
                                                                                </tr>
                                                                                
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
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
                        <form action="{{ url('/admin/checker/ronde/penilaian/submit-process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <input type="hidden" name="branch_assessment_id" value="{{$round->branch_assessment_id }}">
                            <div class="card-footer float-end">
                                @if ($revisi == 'Ya' || $round->status !='Proses Penilaian')
                                <button type="submit" class="btn btn-primary btn-sm" hidden>Submit</button>
                                @else
                                    @if ($lengkap=='ya')
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                        
                                    @endif
                                @endif
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>

@endsection