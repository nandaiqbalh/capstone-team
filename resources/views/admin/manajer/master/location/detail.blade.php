@extends('admin.base.app')

@section('title')
    Lokasi
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Lokasi</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Lokasi</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/lokasi') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td width="15%">Lokasi</td>
                                        <td width="5%">:</td>
                                        <td>{{$location->name}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Keterangan</td>
                                        <td width="5%">:</td>
                                        <td>{{$location->description}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <br>
                        <hr>
                        <br>
                        <p>Lokasi {{$location->name}} memiliki area :</p>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Area</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_area->count() > 0)
                                        @foreach($rs_area as $index => $area)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_area->firstItem() }}.</td>
                                            <td>{{ $area->name }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="4">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto ">
                                <p>Menampilkan {{ $rs_area->count() }} dari total {{ $rs_area->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_area->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection