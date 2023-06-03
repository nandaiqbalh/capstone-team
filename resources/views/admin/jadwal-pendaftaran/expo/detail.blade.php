
@extends('admin.base.app')

@section('title')
    Expo
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4">Expo</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Expo</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/jadwal-pendaftaran/expo') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
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
                                        <td>Siklus </td>
                                        <td>:</td>
                                        <td>{{ $expo->tahun_ajaran }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Mulai</td>
                                        <td>:</td>
                                        <td>{{ $expo->tanggal_mulai }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Selesai</td>
                                        <td>:</td>
                                        <td>{{ $expo->tanggal_selesai }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Nomor Kelompok</th>
                                        <th>Status</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_kel_expo->count() > 0)
                                    @foreach($rs_kel_expo as $index => $pendaftaran)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $pendaftaran->nomor_kelompok }}</td>
                                        <td>{{ $pendaftaran->status }}</td>
                                        <td class="text-center">
                                            @if ($pendaftaran->status=='menunggu persetujuan')
                                            <a href="{{ url('/admin/jadwal-pendaftaran/expo/terima') }}/{{ $expo->id_pendaftaran }}" class="btn btn-outline-primary btn-xs m-1 "onclick="return confirm('Apakah anda ingin menerima {{ $pendaftaran->nomor_kelompok }} ?')">  Terima</a>
                                            <a href="{{ url('/admin/jadwal-pendaftaran/expo/tolak') }}/{{ $expo->id_pendaftaran }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menolak {{ $pendaftaran->nomor_kelompok }} ?')"> Tolak</a>
                                            @endif
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


                    </div>
                </div>
            </div>
@endsection