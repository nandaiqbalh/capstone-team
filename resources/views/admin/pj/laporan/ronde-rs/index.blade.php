@extends('admin.base.app')

@inject('dtid','App\Helpers\DateIndonesia')

@section('title')
    Rekapitulasi Hasil Penilaian
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan /</span> Rekapitulasi Hasil Penilaian</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">Rekapitulasi Hasil Penilaian</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/checker/laporan/ronde/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        {{-- <div class="col-md-4 mt-2">
                                            <input class="form-control mr-sm-2" type="search" name="query_all" value="{{ !empty($query_all) ? $query_all : '' }}" placeholder="Cari ..." minlength="1">
                                        </div> --}}
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
                                        <th>Ronde</th>
                                        <th width="10%">Laporan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_laporan_ronde->count() > 0)
                                        @foreach($rs_laporan_ronde as $index => $laporan_ronde)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_laporan_ronde->firstItem() }}</td>
                                            <td>{{ $laporan_ronde->round_name }}</td>
                                            <td class="text-center">
                                                <a href="{{url('/admin/checker/laporan/ronde/unduh-laporan')}}/{{ $laporan_ronde->id }}" class="btn btn-outline-secondary btn-xs m-1">Unduh Laporan</a>
                                                <a href="#" class="btn btn-outline-secondary btn-xs m-1 "  data-bs-toggle="modal" data-bs-target="#unduhLampiranModal{{ $laporan_ronde->id }}">Unduh Lampiran</a>
                                            </td>
                                            
                                            <input type="hidden" id="judul-lampiran-{{ $laporan_ronde->id }}" name="judul-lampiran-{{ $laporan_ronde->id }}" value="Executive Report Checklist Pengawasan Program ABRT-RL {{$laporan_ronde->branch_name}} {{$laporan_ronde->round_name}} Bulan {{$dtid->get_month_year($laporan_ronde->created_date)}}">
                                            <!-- Modal -->
                                            <div class="modal fade" id="unduhLampiranModal{{ $laporan_ronde->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="unduhLampiranModalLabel{{ $laporan_ronde->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="unduhLampiranModalLabel{{ $laporan_ronde->id }}">Unduh Lampiran</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="box-lampiran-opt-{{ $laporan_ronde->id }}">
                                                                <ul class="list-group">
                                                                    <li class="list-group-item">
                                                                        <input class="form-check-input me-1 lampiran-opt" id="a-opt" name="{{ $laporan_ronde->id }}-lampiran-opt[]" type="checkbox" value="A" aria-label="A" checked>
                                                                        Pembersihan (Nilai A)
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <input class="form-check-input me-1 lampiran-opt" id="b-opt" name="{{ $laporan_ronde->id }}-lampiran-opt[]" type="checkbox" value="B" aria-label="B" checked>
                                                                        Perbaikan (Nilai B)
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <input class="form-check-input me-1 lampiran-opt" id="c-opt" name="{{ $laporan_ronde->id }}-lampiran-opt[]" type="checkbox" value="C" aria-label="C" checked>
                                                                        Penggantian (Nilai C)
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <input class="form-check-input me-1 lampiran-opt" id="d-opt" name="{{ $laporan_ronde->id }}-lampiran-opt[]" type="checkbox" value="D" aria-label="D" checked>
                                                                        Belum Dinilai
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <br>
                                                            <div class="box-loading-{{ $laporan_ronde->id }} d-none">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="spinner-border text-primary" role="status">
                                                                        <span class="visually-hidden">Loading...</span>
                                                                    </div>
                                                                </div>
                                                                <p class="text-center mt-2">Mengunduh. . . </p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary btn-sm btn-unduh-lampiran" id="btn-unduh-lampiran-{{ $laporan_ronde->id }}" data-id="{{ $laporan_ronde->id }}">Unduh Lampiran</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="3">Tidak ada data.</td>
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
                        url: "{{url('/admin/checker/laporan/ronde/ajax-unduh-lampiran')}}",
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