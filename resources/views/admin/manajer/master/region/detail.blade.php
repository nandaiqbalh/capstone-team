@extends('admin.base.app')

@section('title')
    Regional
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Regional</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Regional</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/region') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody class="align-baseline">
                                    <tr>
                                        <td width="15%">Regional</td>
                                        <td width="5%">:</td>
                                        <td>{{$region->name}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Direktur Regional</td>
                                        <td width="5%">:</td>
                                        <td>{{$region->direg_name}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Keterangan</td>
                                        <td width="5%">:</td>
                                        <td>{{$region->description}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
@endsection