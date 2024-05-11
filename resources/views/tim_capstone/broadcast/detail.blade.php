@extends('tim_capstone.base.app')

@section('title')
    Broadcast
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Broadcast</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Broadcast</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/tim-capstone/broadcast') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <!-- table info -->
                <div class="table-responsive">
                    <table class="table table-borderless table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="15%" class="white-bg"></th>
                                <th width="3%" class="white-bg"></th>
                                <th class="white-bg"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="vertical-align: top; text-align: left;"><strong> Nama Event </strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                <td style="vertical-align: top;">{{ $broadcast->nama_event }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; text-align: left;"><strong>Link Pendukung</strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                <td style="vertical-align: top;">
                                    @if ($broadcast->link_pendukung != null)
                                        <a href="http://{{ $broadcast->link_pendukung }}"
                                            target="_blank">{{ $broadcast->link_pendukung }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; text-align: left;"><strong>Tanggal Mulai</strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                <td style="vertical-align: top;">{{ $broadcast->tgl_mulai }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; text-align: left;"><strong>Tanggal Selesai</strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                <td style="vertical-align: top;">{{ $broadcast->tgl_selesai }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; text-align: left;"><strong>Keterangan</strong></td>
                                <td style="vertical-align: top;"><strong>:</strong></td>
                                {{-- <td style="vertical-align: top;">{!! $broadcast->keterangan !!}</td> --}}
                            </tr>
                            <tr>
                                <td colspan="3" style="vertical-align: top;">
                                    @if ($broadcast->broadcast_image_name)
                                        <img src="/img/broadcast/{{ $broadcast->broadcast_image_name }}" alt="Gambar Event"
                                            style="max-width: 100%; max-height: 250px; border-radius: 10px;">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="vertical-align: top;">
                                    {!! $broadcast->keterangan !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <style>
            .white-bg {
                background-color: white !important;
            }
        </style>
    </div>
@endsection
