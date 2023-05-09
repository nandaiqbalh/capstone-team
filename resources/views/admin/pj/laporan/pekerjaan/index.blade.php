@extends('admin.base.app')
@inject('dtid','App\Helpers\DateIndonesia')

@section('title')
Rekapitulasi Hasil Pekerjaan
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan / </span>Rekapitulasi Hasil Pekerjaan</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">Rekapitulasi Hasil Pekerjaan</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/checker/laporan/pekerjaan/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="row">
        
                                                {{-- <div class="col-md-4 mt-2">
                                                    <select class="form-control form-select mr-sm-2 select-2" name="region_name">
                                                        <option value="" disabled selected>Pilih Regional</option>
                                                        @foreach($rs_region as $index => $region)
                                                        <option value="{{$region->name}}" @if( !empty($region_name) && $region_name == $region->name ) selected @endif>{{$region->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}
        
                                                {{-- <div class="col-md-4 mt-2">
                                                    <select class="form-control form-select mr-sm-2 select-2" name="branch_id">
                                                        <option value="" disabled selected>Pilih Bamasama</option>
                                                        @foreach($rs_branch as $index => $branch)
                                                        <option value="{{$branch->id}}" @if( !empty($branch_id) && $branch_id == $branch->id ) selected @endif>{{$branch->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}
        
                                                {{-- <div class="col-md-4 mt-2">
                                                    <select class="form-control form-select mr-sm-2" name="ronde_id">
                                                        <option value="" selected>Semua Ronde </option>
                                                        <option value="1" @if( !empty($ronde_id) && $ronde_id == '1' ) selected @endif>Ronde 1</option>
                                                        <option value="2" @if( !empty($ronde_id) && $ronde_id == '2' ) selected @endif>Ronde 2</option>
                                                        <option value="3" @if( !empty($ronde_id) && $ronde_id == '3' ) selected @endif>Ronde 3</option>
                                                        <option value="4" @if( !empty($ronde_id) && $ronde_id == '4' ) selected @endif>Ronde 4</option>
                                                    </select>
                                                </div> --}}
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4 mt-2">
                                                    <select class="form-control form-select mr-sm-2 select-2" name="month" required>
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
        
                                                <div class="col-md-4 mt-2">
                                                    <select class="form-control form-select mr-sm-2 select-2" name="year" required>
                                                        <option value="" disabled selected>Pilih Tahun</option>
                                                        @foreach($rs_year as $index => $yeardb)
                                                        <option value="{{$yeardb->name}}" @if( !empty($year) && $year == $yeardb->name ) selected @endif>{{$yeardb->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="mt-2">
                                                <button class="btn btn-outline-secondary ml-1" type="submit" name="action" value="search">
                                                    <i class="bx bx-search-alt-2"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary ml-1" type="submit" name="action" value="reset">
                                                    <i class="bx bx-reset"></i>
                                                </button>

                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                        {{-- <div class="row justify-content-end mb-2 mt-5">
                            <div class="col-auto ">
                                <a href="#" class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#erRangkumanBCDModal"><i class="bx bx-download"></i> Rekapitulasi</a>
                            </div>
                        </div> --}}
                        <br>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Ronde</th>
                                        <th width="10%">Laporan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_laporan_pekerjaan->count() > 0)
                                        @foreach($rs_laporan_pekerjaan as $index => $laporan_pekerjaan)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_laporan_pekerjaan->firstItem() }}</td>
                                            <td>{{ $laporan_pekerjaan->round_name }}</td>
                                            <td class="text-center">
                                                <a href="{{url('/admin/checker/laporan/pekerjaan/unduh-laporan')}}/{{ $laporan_pekerjaan->id }}" class="btn btn-outline-secondary btn-xs m-1">Unduh Laporan</a>
                                                <a href="#" class="btn btn-outline-secondary btn-xs m-1 "  data-bs-toggle="modal" data-bs-target="#unduhLampiranModal{{ $laporan_pekerjaan->id }}">Unduh Lampiran</a>
                                            </td>
                                            
                                            <input type="hidden" id="judul-lampiran-{{ $laporan_pekerjaan->id }}" name="judul-lampiran-{{ $laporan_pekerjaan->id }}" value="Executive Report Checklist Pengawasan Program ABRT-RL {{$laporan_pekerjaan->branch_name}} {{$laporan_pekerjaan->round_name}} Bulan {{$dtid->get_month_year($laporan_pekerjaan->created_date)}}">
                                            <!-- Modal -->
                                            <div class="modal fade" id="unduhLampiranModal{{ $laporan_pekerjaan->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="unduhLampiranModalLabel{{ $laporan_pekerjaan->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="unduhLampiranModalLabel{{ $laporan_pekerjaan->id }}">Unduh Lampiran</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="box-lampiran-opt-{{ $laporan_pekerjaan->id }}">
                                                                <ul class="list-group">
                                                                    <li class="list-group-item">
                                                                        <input class="form-check-input me-1 lampiran-opt" id="b-opt" name="{{ $laporan_pekerjaan->id }}-lampiran-opt[]" type="checkbox" value="B" aria-label="B" checked>
                                                                        Perbaikan (Nilai B)
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                         <input class="form-check-input me-1 lampiran-opt" id="c-opt" name="{{ $laporan_pekerjaan->id }}-lampiran-opt[]" type="checkbox" value="C" aria-label="C" checked>
                                                                        Penggantian (Nilai C)
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <input class="form-check-input me-1 lampiran-opt" id="d-opt" name="{{ $laporan_pekerjaan->id }}-lampiran-opt[]" type="checkbox" value="Selesai" aria-label="Selesai" checked>
                                                                        Selesai
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <input class="form-check-input me-1 lampiran-opt" id="d-opt" name="{{ $laporan_pekerjaan->id }}-lampiran-opt[]" type="checkbox" value="Belum Dikerjakan" aria-label="Belum Dikerjakan" checked>
                                                                        Belum Dikerjakan
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <br>
                                                            <div class="box-loading-{{ $laporan_pekerjaan->id }} d-none">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border text-primary" role="status">
                                                                        <span class="visually-hidden">Loading...</span>
                                                                    </div>
                                                                </div>
                                                                <p class="text-center mt-2">Mengunduh. . . </p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary btn-sm btn-unduh-lampiran" id="btn-unduh-lampiran-{{ $laporan_pekerjaan->id }}" data-id="{{ $laporan_pekerjaan->id }}">Unduh</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="6">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto ">
                                <p>Menampilkan {{ $rs_laporan_pekerjaan->count() }} dari total {{ $rs_laporan_pekerjaan->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_laporan_pekerjaan->links() }}
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="erRangkumanBCDModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="erRangkumanBCDModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="erRangkumanBCDModalLabel">Rekapitulasi Seluruh Bamasama</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{url('/admin/checker/laporan/pekerjaan/unduh-laporan-rekapitulasi-nilai')}}" method="post" id="form-rangkuman-abcd">
                        {{ csrf_field()}}
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Regional</label>
                                    <select class="form-control form-select mr-sm-2 select-2-modal-1" name="m_region_name" required>
                                        <option value="" disabled selected>Pilih </option>
                                        <option value="0" >Semua Regional</option>
                                        @foreach($rs_region as $index => $region)
                                        <option value="{{$region->name}}">{{$region->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ronde</label>
                                    <select class="form-control form-select mr-sm-2" name="m_ronde_id" required>
                                        <option value="" disabled selected>Pilih </option>
                                        <option value="0" >Semua Ronde</option>
                                        <option value="1" >Ronde 1</option>
                                        <option value="2" >Ronde 2</option>
                                        <option value="3" >Ronde 3</option>
                                        <option value="4" >Ronde 4</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bulan</label>
                                    <select class="form-control form-select mr-sm-2 select-2-modal-2" name="m_month" required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="01" @if( date('m') == '01' ) selected @endif>Januari</option>
                                        <option value="02" @if( date('m') == '02' ) selected @endif>Februari</option>
                                        <option value="03" @if( date('m') == '03' ) selected @endif>Maret</option>
                                        <option value="04" @if( date('m') == '04' ) selected @endif>April</option>
                                        <option value="05" @if( date('m') == '05' ) selected @endif>Mei</option>
                                        <option value="06" @if( date('m') == '06' ) selected @endif>Juni</option>
                                        <option value="07" @if( date('m') == '07' ) selected @endif>Juli</option>
                                        <option value="08" @if( date('m') == '08' ) selected @endif>Agustus</option>
                                        <option value="09" @if( date('m') == '09' ) selected @endif>September</option>
                                        <option value="10" @if( date('m') == '10' ) selected @endif>Oktober</option>
                                        <option value="11" @if( date('m') == '11' ) selected @endif>November</option>
                                        <option value="12" @if( date('m') == '12' ) selected @endif>Desember</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tahun</label>
                                    <select class="form-control form-select mr-sm-2 select-2-modal-3" name="m_year" required>
                                        <option value="" disabled selected>Pilih</option>
                                        @foreach($rs_year as $index => $yeardb)
                                        <option value="{{$yeardb->name}}" @if( date('Y') == $yeardb->name ) selected @endif>{{$yeardb->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
    
                                <br>
                                <div class="box-loading d-none">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <p class="text-center mt-2">Mengunduh. . . </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sm">Unduh</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                $(".select-2-modal-1").select2({
                    theme: "bootstrap-5",
                    dropdownParent: $(".select-2-modal-1").parent(), // Required for dropdown styling
                });
                $(".select-2-modal-2").select2({
                    theme: "bootstrap-5",
                    dropdownParent: $(".select-2-modal-2").parent(), // Required for dropdown styling
                });
                $(".select-2-modal-3").select2({
                    theme: "bootstrap-5",
                    dropdownParent: $(".select-2-modal-3").parent(), // Required for dropdown styling
                });
            </script>

            <script type="text/javascript">

                // btn unduh 
                $(".btn-unduh-lampiran").on('click', function(){
                    // get id
                    var id = $(this).data('id');

                    // hide opt
                    $(".box-lampiran-opt-"+id).addClass('d-none');
                    $("#btn-unduh-lampiran-"+id).addClass('d-none');

                    // show loading
                    $(".box-loading-"+id).removeClass('d-none');

                    unduhLampiran(id);
                });

                // ajax unduh laporan
                function unduhLampiran(id) {
                    // ambil yang dipilih
                    var listLampiranChecked = $("input[name='"+id+"-lampiran-opt[]']:checked").map(function () {
                                                return this.value;
                                            }).get();

                    // filename
                    var fileName = "Lampiran ("+listLampiranChecked.join(',')+") "+$("#judul-lampiran-"+id).val();
    
                    $.ajax({
                        type: 'POST',
                        url: "{{url('/admin/checker/laporan/pekerjaan/ajax-unduh-lampiran')}}",
                        data : {
                            _token : '{{csrf_token()}}',
                            id : id,
                            opt: listLampiranChecked
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(response, textStatus, xhr) {
                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(response);
                            a.href = url;
                            a.download = fileName;
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);

                            // hide loading
                            $(".box-loading-"+id).addClass('d-none');

                            // show opt
                            $(".box-lampiran-opt-"+id).removeClass('d-none');
                            $("#btn-unduh-lampiran-"+id).removeClass('d-none');

                        },
                        error : function(jqXHR, textStatus, errorThrown){
                            // hide loading
                            $(".box-loading-"+id).addClass('d-none');

                            // show opt
                            $(".box-lampiran-opt-"+id).removeClass('d-none');
                            $("#btn-unduh-lampiran-"+id).removeClass('d-none');

                        }
                    });

                }

            </script>
    
@endsection