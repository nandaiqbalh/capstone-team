@extends('admin.base.app')

@section('title')
    QR Code
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">QR Code</span></h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">QR Code Sub Area</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/checker/qr-code/search') }}" method="get" autocomplete="off">
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
                        <div class="row justify-content-end mb-2">
                            <div class="col-auto ">
                                <a href="{{ url('/admin/checker/qr-code/download-qr/all') }}" class="btn btn-primary float-right btn-sm"><i class="bx bx-download"></i> Unduh Semua</a>
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Area</th>
                                        <th>Sub Area</th>
                                        <th width="18%">QR Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @if($rs_qr_code->count() > 0)
                                            @foreach($rs_qr_code as $index => $qr_code)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}.</td>
                                                <td>{{ $qr_code["area"] }}</td>
                                                <td>{{ $qr_code["sub_area"] }}</td>
                                                <td class="text-center">
                                                    {{-- <a href="{{ url('/admin/checker/register/akun-rs/edit') }}/{{ $qr_code->user_id }}" class="btn btn-outline-warning btn-xs m-1 "> Ubah</a> --}}
                                                    
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-outline-warning btn-xs m-1 " data-bs-toggle="modal" data-bs-target="#modal{{$index}}">
                                                    Tampilkan
                                                    </button>
                                                    
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modal{{$index}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            {{-- <h5 class="modal-title" id="exampleModalLabel">QR Code</h5> --}}
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body" id="qrCode">
                                                                <div>

                                                                    {{ $qr_code["qrcodeGenerate"] }}
                                                                </div>
                                                                <div class="mt-4">

                                                                    <h5>{{ $qr_code["sub_area"] }}</h5>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                                                            <form action="{{ url('/admin/checker/qr-code/download-qr') }}/{{$qr_code["sub_area_id"]}}" method="post" autocomplete="off">
                                                                {{ csrf_field()}}
                                                                <input type="hidden" name="sub_area" value='{{$qr_code["sub_area"]}}'>
                                                                <button type="submit" id="btnSave" class="btn btn-primary">Unduh</button>
                                                            </form>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center" colspan="4">Tidak ada data.</td>
                                            </tr>
                                        @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto mr-auto">
                                <p>Menampilkan {{ $rs_qr_code->count() }} dari total {{ $rs_qr_code->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_qr_code->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection