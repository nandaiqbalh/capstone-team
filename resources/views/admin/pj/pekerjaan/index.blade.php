@extends('admin.base.app')
@inject('dtid', 'App\Helpers\DateIndonesia')

@section('title')
    Rekapitulasi Pekerjaan
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Rekapitulasi Pekerjaan</span></h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Ronde {{ $round->round_id }} Bulan {{ $dtid->get_month_year(date('Y-m-d')) }}
                ({{ $total_assignment_selesai }}/{{ $total_assignment_all }})<br>
                <small class="form-text text-muted">{{ ucwords($round->status) }}</small>
            </h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/checker/ronde/pekerjaan/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="search" value="{{ !empty($search) ? $search : '' }}" placeholder="Cari ..." minlength="1" >
                                </div>
                                <div class="col-md-3 mt-1">
                                    <select class="form-select" name="status_pekerjaan">
                                        <option value="0" selected>Semua Status Pekerjaan</option>
                                        <option value="Selesai" @if( !empty($search_status_pekerjaan) && $search_status_pekerjaan == 'Selesai' ) selected @endif>Selesai</option>
                                        <option value="Belum Dikerjakan" @if( !empty($search_status_pekerjaan) && $search_status_pekerjaan == 'Belum Dikerjakan' ) selected @endif>Belum Dikerjakan</option>
                                        <option value="3" @if( !empty($search_status_pekerjaan) && $search_status_pekerjaan == '3' ) selected @endif>Keterangan Kosong</option>
                                    </select>
                                </div>
                                <div class="col-auto mt-1">
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="search">
                                        <i class="bx bx-search-alt-2"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="reset">
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
                                <th>Area/Sub Area</th>
                                <th>Item Penilaian</th>
                                <th>Komponen Penilaian</th>
                                <th>Pekerjaan</th>
                                <th>Status Pengerjaan</th>
                                <th>Revisi</th>
                                <th width="10%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_assignment->count() > 0)
                                @foreach ($rs_assignment as $index => $assignment)
                                    @if ($assignment->keterangan &&  $assignment->nama_gambar)
                                    <tr style="background: #e3eef080">
                                        <td class="text-center">{{ $index + $rs_assignment->firstItem() }}.</td>
                                        <td>{{ $assignment->nama_lokasi }}</td>
                                        <td>{{ $assignment->nama_area }} / {{ $assignment->nama_sub_area }}</td>
                                        <td>{{ $assignment->nama_item }} #{{ $assignment->unique_id }}</td>
                                        <td>{{ $assignment->nama_komponen }}</td>
                                        <td>@if ( $assignment->score == 'B')
                                            Perbaikan
                                            @elseif ( $assignment->score == 'C')
                                            Pergantian
                                            @else
                                            Tidak Diketahui
                                        @endif</td>
                                        <td>{{ $assignment->status }}</td>
                                        <td>{{ $assignment->status_revisi }}</td>
                                        <td class="text-center">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-outline-warning btn-xs m-1 "
                                                data-bs-toggle="modal" data-bs-target="#modal{{ $index }}">
                                                Detail
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modal{{ $index }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="qrCode">
                                                            {{-- <div>
                                                                    <div class="text-center">
                                                                        <img style="max-height: 260px" src="{{$vps_img}}/{{ $assignment->nama_gambar }}" class="img-thumbnail mx-auto d-block" alt="{{ $assignment->nama_gambar }}">
                                                                    </div>
                                                                    <div class="text-center">
                                                                        <img style="max-height: 260px" src="{{$vps_img}}/{{ $assignment->nama_gambar }}" class="img-thumbnail mx-auto d-block" alt="{{ $assignment->nama_gambar }}">
                                                                    </div>
                                                                </div> --}}
                                                            <div class="d-flex p-2 gap-2 justify-content-center">
                                                                <div class="text-center">
                                                                    <img style="max-height: 260px"
                                                                        src="{{ $vps_img }}{{ $assignment->img_before }}"
                                                                        class="img-thumbnail mx-auto d-block"
                                                                        alt="{{ $assignment->nama_gambar }}">
                                                                </div>
                                                                @if ($assignment->status == 'Selesai')
                                                                    <div class="text-center">
                                                                        <img style="max-height: 260px"
                                                                            src="{{ $vps_img }}{{ $assignment->nama_gambar }}"
                                                                            class="img-thumbnail mx-auto d-block"
                                                                            alt="{{ $assignment->nama_gambar }}">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex p-2 gap-2">
                                                                <div class="text-center p-2 flex-grow-1">
                                                                    <p><b>Sebelum</b> </p>
                                                                </div>
                                                                @if ($assignment->status == 'Selesai')
                                                                    <div class="text-center p-2 flex-grow-1">
                                                                        <p><b>Sesudah</b></p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="mt-4">
                                                                <div class="table-responsive">
                                                                    <table class="table table-borderless table-hover ">
                                                                        <tbody class="align-baseline"
                                                                            style="text-align: left;">
                                                                            <tr>
                                                                                    <td width="35%">Pekerjaan</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->score }} {{($assignment->score == "B") ? ("- Perbaikan") : ("- Pergantian")}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Area</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_sub_area }}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Sub Area</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_sub_area }}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Item Penilaian</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_item }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Komponan Penilaian</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_komponen }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Status Pekerjaan</td>
                                                                                    <td width="5%">:</td>

                                                                                    <td>{{ $assignment->status }}</td>

                                                                                </tr>

                                                                                <tr>
                                                                                    <td>Parameter</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->parameter }}</td> --}}
                                                                                    @if ($assignment->parameter)
                                                                                        <td>{{ $assignment->parameter }}
                                                                                        </td>
                                                                                    @else
                                                                                        <td>-</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Keterangan Penilaian</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->keterangan }}</td> --}}
                                                                                    @if ($assignment->keterangan_penilaian)
                                                                                        <td>{{ $assignment->keterangan_penilaian }}
                                                                                        </td>
                                                                                    @else
                                                                                        <td>-</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Keterangan Pekerjaan</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->keterangan }}</td> --}}
                                                                                    @if ($assignment->keterangan)
                                                                                        <td>{{ $assignment->keterangan }}
                                                                                        </td>
                                                                                    @else
                                                                                        <td>-</td>
                                                                                    @endif
                                                                                </tr>

                                                                            <tr>
                                                                                @if ($assignment->revisi_deskripsi)
                                                                                    <td>Keterangan Revisi Pekerjaan</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->revisi_deskripsi }}</td> --}}
                                                                                    @if ($assignment->revisi_deskripsi)
                                                                                        <td>{{ $assignment->revisi_deskripsi }}
                                                                                        </td>
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
                                            
                                            {{-- @if($assignment->status == 'Belum Dikerjakan')
                                                <!-- keterangan -->
                                                <!-- cek -->
                                                @if($round->status == 'Proses Pekerjaan' || $assignment->status_revisi == 'Proses')
                                                    <button type="button" class="btn btn-outline-secondary btn-xs m-1 " data-bs-toggle="modal" data-bs-target="#modalKeterangan{{$assignment->assignment_detail_id}}">
                                                        Keterangan
                                                    </button>
                                                    <!-- modal keterangan -->
                                                    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalKeterangan{{$assignment->assignment_detail_id}}" tabindex="-1" aria-labelledby="modalKeteranganLabel{{$assignment->assignment_detail_id}}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalKeteranganLabel{{$assignment->assignment_detail_id}}">Keterangan</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ url('/admin/checker/ronde/pekerjaan/add-keterangan-process') }}" method="post" autocomplete="off">
                                                                    {{ csrf_field()}}
                                                                    <input type="hidden" name="id" value="{{$assignment->assignment_detail_id}}" required>
                                                                    <input type="hidden" name="status_revisi" value="{{$assignment->status_revisi}}" required>
                                                                    <div class="modal-body ">
                                                                        <div class="mb-3" style="text-align: left !important">
                                                                            <textarea class="form-control" name="description" rows="3" placeholder="Berikan keterangan mengapa pekerjaan belum dikerjakan." required>{{$assignment->keterangan}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif --}}

                                        </td>
                                    </tr>
                                    @elseif($assignment->keterangan)
                                    <tr style="background: #edd6d680">
                                        <td class="text-center">{{ $index + $rs_assignment->firstItem() }}.</td>
                                        <td>{{ $assignment->nama_lokasi }}</td>
                                        <td>{{ $assignment->nama_area }} / {{ $assignment->nama_sub_area }}</td>
                                        <td>{{ $assignment->nama_item }} #{{ $assignment->unique_id }}</td>
                                        <td>{{ $assignment->nama_komponen }}</td>
                                        <td>@if ( $assignment->score == 'B')
                                            Perbaikan
                                            @elseif ( $assignment->score == 'C')
                                            Pergantian
                                            @else
                                            Tidak Diketahui
                                        @endif</td>
                                        <td>{{ $assignment->status }}</td>
                                        <td>{{ $assignment->status_revisi }}</td>
                                        <td class="text-center">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-outline-warning btn-xs m-1 "
                                                data-bs-toggle="modal" data-bs-target="#modal{{ $index }}">
                                                Detail
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modal{{ $index }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="qrCode">
                                                            {{-- <div>
                                                                    <div class="text-center">
                                                                        <img style="max-height: 260px" src="{{$vps_img}}/{{ $assignment->nama_gambar }}" class="img-thumbnail mx-auto d-block" alt="{{ $assignment->nama_gambar }}">
                                                                    </div>
                                                                    <div class="text-center">
                                                                        <img style="max-height: 260px" src="{{$vps_img}}/{{ $assignment->nama_gambar }}" class="img-thumbnail mx-auto d-block" alt="{{ $assignment->nama_gambar }}">
                                                                    </div>
                                                                </div> --}}
                                                            <div class="d-flex p-2 gap-2 justify-content-center">
                                                                <div class="text-center">
                                                                    <img style="max-height: 260px"
                                                                        src="{{ $vps_img }}{{ $assignment->img_before }}"
                                                                        class="img-thumbnail mx-auto d-block"
                                                                        alt="{{ $assignment->nama_gambar }}">
                                                                </div>
                                                                @if ($assignment->status == 'Selesai')
                                                                    <div class="text-center">
                                                                        <img style="max-height: 260px"
                                                                            src="{{ $vps_img }}{{ $assignment->nama_gambar }}"
                                                                            class="img-thumbnail mx-auto d-block"
                                                                            alt="{{ $assignment->nama_gambar }}">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex p-2 gap-2">
                                                                <div class="text-center p-2 flex-grow-1">
                                                                    <p><b>Sebelum</b> </p>
                                                                </div>
                                                                @if ($assignment->status == 'Selesai')
                                                                    <div class="text-center p-2 flex-grow-1">
                                                                        <p><b>Sesudah</b></p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="mt-4">
                                                                <div class="table-responsive">
                                                                    <table class="table table-borderless table-hover ">
                                                                        <tbody class="align-baseline"
                                                                            style="text-align: left;">
                                                                            <tr>
                                                                                    <td width="35%">Pekerjaan</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->score }} {{($assignment->score == "B") ? ("- Perbaikan") : ("- Pergantian")}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Area</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_sub_area }}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Sub Area</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_sub_area }}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Item Penilaian</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_item }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Komponan Penilaian</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_komponen }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Status Pekerjaan</td>
                                                                                    <td width="5%">:</td>

                                                                                    <td>{{ $assignment->status }}</td>

                                                                                </tr>

                                                                                <tr>
                                                                                    <td>Parameter</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->parameter }}</td> --}}
                                                                                    @if ($assignment->parameter)
                                                                                        <td>{{ $assignment->parameter }}
                                                                                        </td>
                                                                                    @else
                                                                                        <td>-</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Keterangan Penilaian</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->keterangan }}</td> --}}
                                                                                    @if ($assignment->keterangan_penilaian)
                                                                                        <td>{{ $assignment->keterangan_penilaian }}
                                                                                        </td>
                                                                                    @else
                                                                                        <td>-</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Keterangan Pekerjaan</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->keterangan }}</td> --}}
                                                                                    @if ($assignment->keterangan)
                                                                                        <td>{{ $assignment->keterangan }}
                                                                                        </td>
                                                                                    @else
                                                                                        <td>-</td>
                                                                                    @endif
                                                                                </tr>

                                                                            <tr>
                                                                                @if ($assignment->revisi_deskripsi)
                                                                                    <td>Keterangan Revisi Pekerjaan</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->revisi_deskripsi }}</td> --}}
                                                                                    @if ($assignment->revisi_deskripsi)
                                                                                        <td>{{ $assignment->revisi_deskripsi }}
                                                                                        </td>
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
                                            
                                            {{-- @if($assignment->status == 'Belum Dikerjakan')
                                                <!-- keterangan -->
                                                <!-- cek -->
                                                @if($round->status == 'Proses Pekerjaan' || $assignment->status_revisi == 'Proses')
                                                    <button type="button" class="btn btn-outline-secondary btn-xs m-1 " data-bs-toggle="modal" data-bs-target="#modalKeterangan{{$assignment->assignment_detail_id}}">
                                                        Keterangan
                                                    </button>
                                                    <!-- modal keterangan -->
                                                    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalKeterangan{{$assignment->assignment_detail_id}}" tabindex="-1" aria-labelledby="modalKeteranganLabel{{$assignment->assignment_detail_id}}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalKeteranganLabel{{$assignment->assignment_detail_id}}">Keterangan</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ url('/admin/checker/ronde/pekerjaan/add-keterangan-process') }}" method="post" autocomplete="off">
                                                                    {{ csrf_field()}}
                                                                    <input type="hidden" name="id" value="{{$assignment->assignment_detail_id}}" required>
                                                                    <input type="hidden" name="status_revisi" value="{{$assignment->status_revisi}}" required>
                                                                    <div class="modal-body ">
                                                                        <div class="mb-3" style="text-align: left !important">
                                                                            <textarea class="form-control" name="description" rows="3" placeholder="Berikan keterangan mengapa pekerjaan belum dikerjakan." required>{{$assignment->keterangan}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif --}}

                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_assignment->firstItem() }}.</td>
                                        <td>{{ $assignment->nama_lokasi }}</td>
                                        <td>{{ $assignment->nama_area }} / {{ $assignment->nama_sub_area }}</td>
                                        <td>{{ $assignment->nama_item }} #{{ $assignment->unique_id }}</td>
                                        <td>{{ $assignment->nama_komponen }}</td>
                                        <td>@if ( $assignment->score == 'B')
                                            Perbaikan
                                            @elseif ( $assignment->score == 'C')
                                            Pergantian
                                            @else
                                            Tidak Diketahui
                                        @endif</td>
                                        <td>{{ $assignment->status }}</td>
                                        <td>{{ $assignment->status_revisi }}</td>
                                        <td class="text-center">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-outline-warning btn-xs m-1 "
                                                data-bs-toggle="modal" data-bs-target="#modal{{ $index }}">
                                                Detail
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modal{{ $index }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="qrCode">
                                                            {{-- <div>
                                                                    <div class="text-center">
                                                                        <img style="max-height: 260px" src="{{$vps_img}}/{{ $assignment->nama_gambar }}" class="img-thumbnail mx-auto d-block" alt="{{ $assignment->nama_gambar }}">
                                                                    </div>
                                                                    <div class="text-center">
                                                                        <img style="max-height: 260px" src="{{$vps_img}}/{{ $assignment->nama_gambar }}" class="img-thumbnail mx-auto d-block" alt="{{ $assignment->nama_gambar }}">
                                                                    </div>
                                                                </div> --}}
                                                            <div class="d-flex p-2 gap-2 justify-content-center">
                                                                <div class="text-center">
                                                                    <img style="max-height: 260px"
                                                                        src="{{ $vps_img }}{{ $assignment->img_before }}"
                                                                        class="img-thumbnail mx-auto d-block"
                                                                        alt="{{ $assignment->nama_gambar }}">
                                                                </div>
                                                                @if ($assignment->status == 'Selesai')
                                                                    <div class="text-center">
                                                                        <img style="max-height: 260px"
                                                                            src="{{ $vps_img }}{{ $assignment->nama_gambar }}"
                                                                            class="img-thumbnail mx-auto d-block"
                                                                            alt="{{ $assignment->nama_gambar }}">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex p-2 gap-2">
                                                                <div class="text-center p-2 flex-grow-1">
                                                                    <p><b>Sebelum</b> </p>
                                                                </div>
                                                                @if ($assignment->status == 'Selesai')
                                                                    <div class="text-center p-2 flex-grow-1">
                                                                        <p><b>Sesudah</b></p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="mt-4">
                                                                <div class="table-responsive">
                                                                    <table class="table table-borderless table-hover ">
                                                                        <tbody class="align-baseline"
                                                                            style="text-align: left;">
                                                                            <tr>
                                                                                    <td width="35%">Pekerjaan</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->score }} {{($assignment->score == "B") ? ("- Perbaikan") : ("- Pergantian")}}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Area</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_sub_area }}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Sub Area</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_sub_area }}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Item Penilaian</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_item }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Komponan Penilaian</td>
                                                                                    <td width="5%">:</td>
                                                                                    <td>{{ $assignment->nama_komponen }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="35%">Status Pekerjaan</td>
                                                                                    <td width="5%">:</td>

                                                                                    <td>{{ $assignment->status }}</td>

                                                                                </tr>

                                                                                <tr>
                                                                                    <td>Parameter</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->parameter }}</td> --}}
                                                                                    @if ($assignment->parameter)
                                                                                        <td>{{ $assignment->parameter }}
                                                                                        </td>
                                                                                    @else
                                                                                        <td>-</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Keterangan Penilaian</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->keterangan }}</td> --}}
                                                                                    @if ($assignment->keterangan_penilaian)
                                                                                        <td>{{ $assignment->keterangan_penilaian }}
                                                                                        </td>
                                                                                    @else
                                                                                        <td>-</td>
                                                                                    @endif
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Keterangan Pekerjaan</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->keterangan }}</td> --}}
                                                                                    @if ($assignment->keterangan)
                                                                                        <td>{{ $assignment->keterangan }}
                                                                                        </td>
                                                                                    @else
                                                                                        <td>-</td>
                                                                                    @endif
                                                                                </tr>

                                                                            <tr>
                                                                                @if ($assignment->revisi_deskripsi)
                                                                                    <td>Keterangan Revisi Pekerjaan</td>
                                                                                    <td>:</td>
                                                                                    {{-- <td>{{ $assignment->revisi_deskripsi }}</td> --}}
                                                                                    @if ($assignment->revisi_deskripsi)
                                                                                        <td>{{ $assignment->revisi_deskripsi }}
                                                                                        </td>
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
                                            
                                            {{-- @if($assignment->status == 'Belum Dikerjakan')
                                                <!-- keterangan -->
                                                <!-- cek -->
                                                @if($round->status == 'Proses Pekerjaan' || $assignment->status_revisi == 'Proses')
                                                    <button type="button" class="btn btn-outline-secondary btn-xs m-1 " data-bs-toggle="modal" data-bs-target="#modalKeterangan{{$assignment->assignment_detail_id}}">
                                                        Keterangan
                                                    </button>
                                                    <!-- modal keterangan -->
                                                    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modalKeterangan{{$assignment->assignment_detail_id}}" tabindex="-1" aria-labelledby="modalKeteranganLabel{{$assignment->assignment_detail_id}}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalKeteranganLabel{{$assignment->assignment_detail_id}}">Keterangan</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ url('/admin/checker/ronde/pekerjaan/add-keterangan-process') }}" method="post" autocomplete="off">
                                                                    {{ csrf_field()}}
                                                                    <input type="hidden" name="id" value="{{$assignment->assignment_detail_id}}" required>
                                                                    <input type="hidden" name="status_revisi" value="{{$assignment->status_revisi}}" required>
                                                                    <div class="modal-body ">
                                                                        <div class="mb-3" style="text-align: left !important">
                                                                            <textarea class="form-control" name="description" rows="3" placeholder="Berikan keterangan mengapa pekerjaan belum dikerjakan." required>{{$assignment->keterangan}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif --}}

                                        </td>
                                    </tr>

                                    @endif
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
                        <p>Menampilkan {{ $rs_assignment->count() }} dari total {{ $rs_assignment->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_assignment->links() }}
                    </div>
                </div>

                {{-- tombol submit ronde --}}
                <form action="{{ url('/admin/checker/ronde/pekerjaan/submit-process') }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="branch_assessment_id" value="{{ $round->branch_assessment_id }}">
                    <div class="card-footer float-end">
                        
                        @if ($revisi == 'Ya' || $round->status !='Proses Pekerjaan')
                                <button type="submit" class="btn btn-primary btn-sm" hidden>Submit</button>
                                @else
                                    @if ($lengkap == 'ya')
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                        
                                    @endif
                                @endif
                    </div>
                </form>
                <div class="col-auto mr-auto">
                    <small>*Keterangan</small><br>
                    <div class="d-flex flex-row bd-highlight">
                        <div style="margin-right:4px; width:20px; height:20px; border-radius: 5px; ; background-color:#e3eef0; border: 0.1px solid rgb(197, 197, 197);"></div><div><small> : Selesai</small></div>
                    </div>
                    <div class="d-flex flex-row bd-highlight">

                        <div style="margin-right:4px; width:20px; height:20px; border-radius: 5px; ; background-color:#edd6d6; border: 0.1px solid rgb(197, 197, 197);"></div><div><small> : Proses Pengerjaan</small></div>
                    </div>
                    <div class="d-flex flex-row bd-highlight">

                        <div style="margin-right:4px; width:20px; height:20px; border-radius: 5px; ; background-color:#ffffff; border: 0.1px solid rgb(197, 197, 197);"></div><div><small> : Belum Dikerjakan</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
