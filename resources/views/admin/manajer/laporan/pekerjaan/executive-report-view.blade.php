@extends('admin.base.app')

<!-- inject helper date indonesia -->
@inject('dtid','App\Helpers\DateIndonesia')

@section('title')
    Laporan Pekerjaan
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan /</span> Pekerjaan</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Laporan Pekerjaan</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/laporan/pekerjaan') }}" class="btn btn-secondary btn-xs "><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <br>
                        <div style="max-height: 900px; overflow-y:scroll;">
                            @include('/admin/validator/laporan/pekerjaan/executive-report-pdf')
                        </div>
                        
                    </div>
                </div>
            </div>
@endsection