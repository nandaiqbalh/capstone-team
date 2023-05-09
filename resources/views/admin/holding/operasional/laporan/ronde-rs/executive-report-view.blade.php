@extends('admin.base.app')

<!-- inject helper date indonesia -->
@inject('dtid','App\Helpers\DateIndonesia')

@section('title')
    Laporan Ronde
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Laporan /</span> Ronde</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Laporan Ronde</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/holding-operasional/laporan/ronde') }}" class="btn btn-secondary btn-xs "><i class="bx bx-chevron-left"></i> Kembali</a>
                            <a href="{{ url('/admin/holding-operasional/laporan/ronde/download-single-er') }}/{{$laporan_ronde->id}}" class="btn btn-primary btn-xs "><i class="bx bx-download"></i> Unduh</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <br>
                        <div style="max-height: 900px; overflow-y:scroll;">
                            @include('/admin/holding-operasional/laporan/ronde-rs/executive-report-pdf')
                        </div>
                        
                    </div>
                </div>
            </div>
@endsection