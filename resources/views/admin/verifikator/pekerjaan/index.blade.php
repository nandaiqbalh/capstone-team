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
            <h5 class="card-header">
                {{ $round->round_id }} Bulan {{ $dtid->get_month_year(date('Y-m-d')) }}
                ({{ $rs_assignment_count }}/{{ $rs_assignment_all }})
                <br>
                <small class="form-text text-muted">{{ ucwords($round->status) }}</small>
            </h5>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/verifikator/ronde/pekerjaan/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="search"
                                        value="{{ !empty($search) ? $search : '' }}" placeholder="Cari ..." required>
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
                                <th width="18%">Tindakan</th>
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
                                                        <div class="alert alert-success d-none"
                                                            id='alert-success{{ $assignment->assignment_id }}'
                                                            role="alert">
                                                            Berhasil Revisi
                                                        </div>
                                                        <div class="alert alert-danger d-none" id='alert-error'
                                                            role="alert">
                                                            Gagal Revisi
                                                        </div>
                                                        <div class="alert alert-danger d-none" id='alert-null'
                                                            role="alert">
                                                            Catatan Revisi Kosong
                                                        </div>
                                                        <form id="revisi{{ $assignment->assignment_id }}"
                                                            autocomplete="off">
                                                            <div class="modal-body" id="qrCode">
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


                                                                                @if ($assignment->revisi_deskripsi)
                                                                                    <tr>
                                                                                        <td>Keterangan Revisi Pekerjaan</td>
                                                                                        <td>:</td>
                                                                                        <td>{{ $assignment->revisi_deskripsi }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                                @if ($role_user == 'Verifikator 1' && $round->status == 'Persetujuan Verifikator 2')
                                                                                @else
                                                                                    @if ($assignment->status_revisi == 'Proses')
                                                                                    @else
                                                                                        @if ($assignment->status)
                                                                                            <tr>
                                                                                                <td>Revisi</td>
                                                                                                <td>:</td>
                                                                                                <td>
                                                                                                    {{ csrf_field() }}
                                                                                                    <input type="hidden"
                                                                                                        name="id{{ $assignment->assignment_id }}"
                                                                                                        value="{{ $assignment->assignment_id }}">
                                                                                                    <div class="form-group">
                                                                                                       <div class="form-group">
                                                                                                            <textarea class="form-control" name="revision{{$assignment->assignment_id }}" rows="3"></textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                @if ($role_user == 'Verifikator 1' && $round->status == 'Persetujuan Verifikator 2')
                                                                @else
                                                                    @if ($round->status == 'Selesai' || $assignment->status_revisi == 'Proses')
                                                                        {{-- <button type="button" data-id='{{$assignment->assignment_id }}' class="btn btn-secondary batalRevisiBtn" data-bs-dismiss="modal" hidden>Batalkan Revisi</button> --}}
                                                                        <button type="button"
                                                                            data-id='{{ $assignment->assignment_id }}'
                                                                            class="btn btn-primary revisiBtn"
                                                                            hidden>Revisi</button>
                                                                    @elseif ($assignment->status_revisi == 'Draft')
                                                                        <button type="button"
                                                                            data-id='{{ $assignment->assignment_id }}'
                                                                            class="btn btn-secondary batalRevisiBtn"
                                                                            data-bs-dismiss="modal">Batalkan Revisi</button>
                                                                        <button type="button"
                                                                            data-id='{{ $assignment->assignment_id }}'
                                                                            class="btn btn-primary revisiBtn"
                                                                            onclick="return confirm('Apakah anda ingin merevisi Pekerjaan ini?')">Revisi</button>
                                                                    @else
                                                                        {{-- <button type="button" data-id='{{$assignment->assignment_id }}' class="btn btn-secondary batalRevisiBtn" data-bs-dismiss="modal">Batalkan Revisi</button> --}}
                                                                        @if ($assignment->status)
                                                                            <button type="button"
                                                                                data-id='{{ $assignment->assignment_id }}'
                                                                                class="btn btn-primary revisiBtn"
                                                                                onclick="return confirm('Apakah anda ingin merevisi Pekerjaan ini?')">Revisi</button>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

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
                                                        <div class="alert alert-success d-none"
                                                            id='alert-success{{ $assignment->assignment_id }}'
                                                            role="alert">
                                                            Berhasil Revisi
                                                        </div>
                                                        <div class="alert alert-danger d-none" id='alert-error'
                                                            role="alert">
                                                            Gagal Revisi
                                                        </div>
                                                        <div class="alert alert-danger d-none" id='alert-null'
                                                            role="alert">
                                                            Catatan Revisi Kosong
                                                        </div>
                                                        <form id="revisi{{ $assignment->assignment_id }}"
                                                            autocomplete="off">
                                                            <div class="modal-body" id="qrCode">
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


                                                                                @if ($assignment->revisi_deskripsi)
                                                                                    <tr>
                                                                                        <td>Keterangan Revisi Pekerjaan</td>
                                                                                        <td>:</td>
                                                                                        <td>{{ $assignment->revisi_deskripsi }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                                @if ($role_user == 'Verifikator 1' && $round->status == 'Persetujuan Verifikator 2')
                                                                                @else
                                                                                    @if ($assignment->status_revisi == 'Proses')
                                                                                    @else
                                                                                        @if ($assignment->status)
                                                                                            <tr>
                                                                                                <td>Revisi</td>
                                                                                                <td>:</td>
                                                                                                <td>
                                                                                                    {{ csrf_field() }}
                                                                                                    <input type="hidden"
                                                                                                        name="id{{ $assignment->assignment_id }}"
                                                                                                        value="{{ $assignment->assignment_id }}">
                                                                                                    <div class="form-group">
                                                                                                       <div class="form-group">
                                                                                                            <textarea class="form-control" name="revision{{$assignment->assignment_id }}" rows="3"></textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                @if ($role_user == 'Verifikator 1' && $round->status == 'Persetujuan Verifikator 2')
                                                                @else
                                                                    @if ($round->status == 'Selesai' || $assignment->status_revisi == 'Proses')
                                                                        {{-- <button type="button" data-id='{{$assignment->assignment_id }}' class="btn btn-secondary batalRevisiBtn" data-bs-dismiss="modal" hidden>Batalkan Revisi</button> --}}
                                                                        <button type="button"
                                                                            data-id='{{ $assignment->assignment_id }}'
                                                                            class="btn btn-primary revisiBtn"
                                                                            hidden>Revisi</button>
                                                                    @elseif ($assignment->status_revisi == 'Draft')
                                                                        <button type="button"
                                                                            data-id='{{ $assignment->assignment_id }}'
                                                                            class="btn btn-secondary batalRevisiBtn"
                                                                            data-bs-dismiss="modal">Batalkan Revisi</button>
                                                                        <button type="button"
                                                                            data-id='{{ $assignment->assignment_id }}'
                                                                            class="btn btn-primary revisiBtn"
                                                                            onclick="return confirm('Apakah anda ingin merevisi Pekerjaan ini?')">Revisi</button>
                                                                    @else
                                                                        {{-- <button type="button" data-id='{{$assignment->assignment_id }}' class="btn btn-secondary batalRevisiBtn" data-bs-dismiss="modal">Batalkan Revisi</button> --}}
                                                                        @if ($assignment->status)
                                                                            <button type="button"
                                                                                data-id='{{ $assignment->assignment_id }}'
                                                                                class="btn btn-primary revisiBtn"
                                                                                onclick="return confirm('Apakah anda ingin merevisi Pekerjaan ini?')">Revisi</button>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

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
                                                        <div class="alert alert-success d-none"
                                                            id='alert-success{{ $assignment->assignment_id }}'
                                                            role="alert">
                                                            Berhasil Revisi
                                                        </div>
                                                        <div class="alert alert-danger d-none" id='alert-error'
                                                            role="alert">
                                                            Gagal Revisi
                                                        </div>
                                                        <div class="alert alert-danger d-none" id='alert-null'
                                                            role="alert">
                                                            Catatan Revisi Kosong
                                                        </div>
                                                        <form id="revisi{{ $assignment->assignment_id }}"
                                                            autocomplete="off">
                                                            <div class="modal-body" id="qrCode">
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


                                                                                @if ($assignment->revisi_deskripsi)
                                                                                    <tr>
                                                                                        <td>Keterangan Revisi Pekerjaan</td>
                                                                                        <td>:</td>
                                                                                        <td>{{ $assignment->revisi_deskripsi }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                                @if ($role_user == 'Verifikator 1' && $round->status == 'Persetujuan Verifikator 2')
                                                                                @else
                                                                                    @if ($assignment->status_revisi == 'Proses')
                                                                                    @else
                                                                                        @if ($assignment->status)
                                                                                            <tr>
                                                                                                <td>Revisi</td>
                                                                                                <td>:</td>
                                                                                                <td>
                                                                                                    {{ csrf_field() }}
                                                                                                    <input type="hidden"
                                                                                                        name="id{{ $assignment->assignment_id }}"
                                                                                                        value="{{ $assignment->assignment_id }}">
                                                                                                    <div class="form-group">
                                                                                                       <div class="form-group">
                                                                                                            <textarea class="form-control" name="revision{{$assignment->assignment_id }}" rows="3"></textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                @if ($role_user == 'Verifikator 1' && $round->status == 'Persetujuan Verifikator 2')
                                                                @else
                                                                    @if ($round->status == 'Selesai' || $assignment->status_revisi == 'Proses')
                                                                        {{-- <button type="button" data-id='{{$assignment->assignment_id }}' class="btn btn-secondary batalRevisiBtn" data-bs-dismiss="modal" hidden>Batalkan Revisi</button> --}}
                                                                        <button type="button"
                                                                            data-id='{{ $assignment->assignment_id }}'
                                                                            class="btn btn-primary revisiBtn"
                                                                            hidden>Revisi</button>
                                                                    @elseif ($assignment->status_revisi == 'Draft')
                                                                        <button type="button"
                                                                            data-id='{{ $assignment->assignment_id }}'
                                                                            class="btn btn-secondary batalRevisiBtn"
                                                                            data-bs-dismiss="modal">Batalkan Revisi</button>
                                                                        <button type="button"
                                                                            data-id='{{ $assignment->assignment_id }}'
                                                                            class="btn btn-primary revisiBtn"
                                                                            onclick="return confirm('Apakah anda ingin merevisi Pekerjaan ini?')">Revisi</button>
                                                                    @else
                                                                        {{-- <button type="button" data-id='{{$assignment->assignment_id }}' class="btn btn-secondary batalRevisiBtn" data-bs-dismiss="modal">Batalkan Revisi</button> --}}
                                                                        @if ($assignment->status)
                                                                            <button type="button"
                                                                                data-id='{{ $assignment->assignment_id }}'
                                                                                class="btn btn-primary revisiBtn"
                                                                                onclick="return confirm('Apakah anda ingin merevisi Pekerjaan ini?')">Revisi</button>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

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
                
                @if ($role_user == 'Verifikator 1')
                    {{-- tombol submit ronde --}}
                    <form action="{{ url('/admin/verifikator/ronde/pekerjaan/approve-1-process') }}" method="post"
                        autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="branch_assignment_id" value="{{ $round->id }}">
                        <div class=" float-end">
                            @if (
                                $revisi == 'Ya' ||
                                    $round->status == 'Selesai' ||
                                    $round->status == 'Proses Pekerjaan' ||
                                    $round->status == 'Persetujuan Verifikator 2')
                                <button type="submit" class="btn btn-primary btn-sm m-1" hidden>Setuju</button>
                            @else
                                <button type="submit" class="btn btn-primary btn-sm m-1">Setuju</button>
                            @endif
                        </div>
                    </form>
                @else
                    {{-- tombol submit ronde --}}
                    <form action="{{ url('/admin/verifikator/ronde/pekerjaan/approve-2-process') }}" method="post"
                        autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="branch_assignment_id" value="{{ $round->id }}">
                        <div class=" float-end">
                            @if (
                                $revisi == 'Ya' ||
                                    $round->status == 'Selesai' ||
                                    $round->status == 'Proses Pekerjaan' ||
                                    $round->status == 'Persetujuan Verifikator 1')
                                <button type="submit" class="btn btn-primary btn-sm m-1" hidden>Setuju</button>
                            @else
                                <button type="submit" class="btn btn-primary btn-sm m-1">Setuju</button>
                            @endif
                        </div>
                    </form>
                @endif
                {{-- @if ($revisi == 'Ya')
                        <div class="float-end">
                            <button type="button" class="btn btn-primary btn-sm m-1" data-bs-toggle="modal" data-bs-target="#exampleModal">Tinjau Revisi</button>
                        </div>
                            
                        @endif --}}
                @if ($rs_assignment_revision->count() > 0)
                    {{-- <a href="{{ url('/admin/verifikator/ronde/pekerjaan/cancel-all') }}" onclick="return confirm('Apakah anda ingin membatalkan semua revisi?')" class="btn btn-secondary">Batalkan Semua</a> --}}
                    <div class="float-end">
                        @if ($role_user == 'Verifikator 1')
                            <a href="{{ url('/admin/verifikator/ronde/pekerjaan/revision-mail') }}"
                                onclick="return confirm('Apakah anda ingin mengirim revisi?')"
                                class="btn btn-secondary btn-sm m-1">Kirim Revisi</a>
                        @else
                            <a href="{{ url('/admin/verifikator/ronde/pekerjaan/revision-mail2') }}"
                                onclick="return confirm('Apakah anda ingin mengirim revisi?')"
                                class="btn btn-secondary btn-sm m-1">Kirim Revisi</a>
                        @endif

                    </div>
                @endif
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
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">List Revisi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th width="5%">No</th>
                                                <th>Lokasi</th>
                                                <th>Area</th>
                                                <th>Sub Area</th>
                                                <th>Item Penilaian</th>
                                                <th>Komponen Penilaian</th>
                                                <th>Revisi</th>
                                                <th width="18%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($rs_assignment_revision->count() > 0)
                                                @foreach ($rs_assignment_revision as $index => $assignment)
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ $index + $rs_assignment->firstItem() }}.</td>
                                                        <td>{{ $assignment->nama_lokasi }}</td>
                                                        <td>{{ $assignment->nama_area }}</td>
                                                        <td>{{ $assignment->nama_sub_area }}</td>
                                                        <td>{{ $assignment->nama_item }}</td>
                                                        <td>{{ $assignment->nama_komponen }}</td>
                                                        <td>{{ $assignment->status_revisi }}</td>
                                                        <td class="text-center">
                                                            <!-- Button trigger modal -->
                                                            <button type="button"
                                                                class="btn btn-outline-warning btn-xs m-1 "
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal{{ $index }}">
                                                                Detail
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-center" colspan="8">Tidak ada data.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                @if ($rs_assignment_revision->count() > 0)
                                    <a href="{{ url('/admin/verifikator/ronde/pekerjaan/cancel-all') }}"
                                        onclick="return confirm('Apakah anda ingin membatalkan semua revisi?')"
                                        class="btn btn-secondary">Batalkan Semua</a>
                                    @if ($role_user == 'Verifikator 1')
                                        <a href="{{ url('/admin/verifikator/ronde/pekerjaan/revision-mail') }}"
                                            class="btn btn-primary">Kirim </a>
                                    @else
                                        <a href="{{ url('/admin/verifikator/ronde/pekerjaan/revision-mail2') }}"
                                            class="btn btn-primary">Kirim </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // add data
        $('.revisiBtn').on('click', function(event) {
            var id = $(this).data('id');
            // console.log(id)
            // event.preventDefault();
            addRevision(id);

        })
        $('.batalRevisiBtn').on('click', function(event) {
            var id = $(this).data('id');
            // console.log(id)
            // event.preventDefault();
            cancelRevision(id);

        })
        var url = "{{ url('/admin/verifikator/ronde/pekerjaan/revision-process') }}";

        function addRevision(id) {
            var _token = $("input[name=_token]").val();
            $.ajax({
                url: url,
                cache: false,
                method: "POST",
                data: {
                    _token: _token,
                    branch_assignment_detail_id: id,
                    branch_assignment_detail_revision: $("textarea[name=revision" + id + "]").val(),
                },
                success: function(response) {
                    // console.log(response.status);
                    // if not found
                    if (response.status == true) {
                        console.log('success');
                        $('#alert-success' + id + '').removeClass('d-none');
                        // auto close alert
                        window.setTimeout(function() {
                            $('#alert-success' + id + '').addClass('d-none');
                        }, 5000);
                        location.reload();
                    } else if (response.status == false) {
                        console.log('false');
                        $('#alert-null').removeClass('d-none');
                        // auto close alert
                        window.setTimeout(function() {
                            $('#alert-null').addClass('d-none');
                        }, 5000);
                        // location.reload();
                    } else {
                        $('#error-alert').removeClass('d-none');
                        // auto close alert
                        window.setTimeout(function() {
                            $('#error-alert').addClass('d-none');
                        }, 5000);
                    }

                }
            });
        }

        function cancelRevision(id) {
            var _token = $("input[name=_token]").val();
            $.ajax({
                url: url,
                cache: false,
                method: "POST",
                data: {
                    _token: _token,
                    branch_assessment_detail_id: id,
                    batal_revisi: true,
                    branch_assessment_detail_revision: $("textarea[name=revision" + id + "]").val(),
                },
                success: function(response) {
                    // console.log(response.status);
                    // if not found
                    if (response.status == true) {
                        console.log('success');
                        $('#alert-success' + id + '').removeClass('d-none');
                        // auto close alert
                        window.setTimeout(function() {
                            $('#alert-success' + id + '').addClass('d-none');
                        }, 5000);
                        location.reload();
                    } else if (response.status == false) {
                        console.log('false');
                        $('#alert-null').removeClass('d-none');
                        // auto close alert
                        window.setTimeout(function() {
                            $('#alert-null').addClass('d-none');
                        }, 5000);
                        // location.reload();
                    } else {
                        $('#error-alert').removeClass('d-none');
                        // auto close alert
                        window.setTimeout(function() {
                            $('#error-alert').addClass('d-none');
                        }, 5000);
                    }

                }
            });
        }
    </script>
@endsection
