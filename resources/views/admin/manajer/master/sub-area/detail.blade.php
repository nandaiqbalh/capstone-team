@extends('admin.base.app')

@section('title')
    Sub Area
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Sub Area</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Sub Area</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/sub-area') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td width="15%">Sub Area</td>
                                        <td width="5%">:</td>
                                        <td>{{$sub_area->name}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Area</td>
                                        <td width="5%">:</td>
                                        <td>{{$sub_area->area_name}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Keterangan</td>
                                        <td width="5%">:</td>
                                        <td>{{$sub_area->description}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <br>
                        
                    </div>
                </div>
            </div>
@endsection