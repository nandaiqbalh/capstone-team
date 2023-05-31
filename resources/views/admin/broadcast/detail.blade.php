
@extends('admin.base.app')

@section('title')
    Broadcast
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Broadcast</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Broadcast</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/broadcast') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
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
                                        <td>Nama Event</td>
                                        <td>:</td>
                                        <td>{{ $broadcast->nama_event }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tangga Mulai</td>
                                        <td>:</td>
                                        <td>{{ $broadcast->tgl_mulai }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tangga Selesai</td>
                                        <td>:</td>
                                        <td>{{ $broadcast->tgl_selesai }}</td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td>:</td>
                                        <td>{{ $broadcast->keterangan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Link</td>
                                        <td>:</td>
                                        <td>{{ $broadcast->link_pendukung }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
@endsection