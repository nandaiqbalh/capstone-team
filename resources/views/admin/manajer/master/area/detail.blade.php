@extends('admin.base.app')

@section('title')
    Area
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Area</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Area</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/area') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody class="align-baseline">
                                    <tr>
                                        <td width="15%">Area</td>
                                        <td width="5%">:</td>
                                        <td>{{$area->name}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Lokasi</td>
                                        <td width="5%">:</td>
                                        <td>{{$area->location_name}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Keterangan</td>
                                        <td width="5%">:</td>
                                        <td>{{$area->description}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Ronde</td>
                                        <td width="5%">:</td>
                                        <td>{{$area->round_name}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <br>
                        <hr>
                        <br>
                        <p>Area {{$area->name}} memiliki sub area :</p>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Sub Area</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_sub_area->count() > 0)
                                        @foreach($rs_sub_area as $index => $sub_area)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_sub_area->firstItem() }}.</td>
                                            <td>{{ $sub_area->name }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="2">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto ">
                                <p>Menampilkan {{ $rs_sub_area->count() }} dari total {{ $rs_sub_area->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_sub_area->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection