@extends('tim_capstone.base.app')

@section('title')
    Siklus
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Siklus</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Siklus</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/siklus') }}" class="btn btn-secondary btn-xs float-right"><i
                            class="bx bx-chevron-left"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <!-- table info -->
                <div class="table-responsive">
                    <table class="table table-borderless table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="20%"></th>
                                <th width="5%"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nama - Tahun Ajaran</td>
                                <td>:</td>
                                <td>{{ $siklus->tahun_ajaran }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Mulai</td>
                                <td>:</td>
                                <td>{{ $siklus->tanggal_mulai }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Selesai</td>
                                <td>:</td>
                                <td>{{ $siklus->tanggal_selesai }}</td>
                            </tr>
                            <tr>
                                <td>Batas Submit C100</td>
                                <td>:</td>
                                <td>{{ $siklus->batas_submit_c100 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
