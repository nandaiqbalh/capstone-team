
@extends('admin.base.app')

@section('title')
    Acara
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4">Detail Acara</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <a href="{{ url('/admin/pj/register/acara/edit') }}/{{$event->id}}" class="btn btn-primary btn-xs"><i class="bx bx-chevron-left"></i> Ubah Acara</a>
                            <a href="{{ url('/admin/pj/register/acara/add-detail') }}/{{$event->id}}" class="btn btn-primary btn-xs"><i class="bx bx-chevron-left"></i> Ubah Detail Acara</a>
                            <a href="{{ url('/admin/pj/register/acara/mulai') }}/{{$event->id}}" class="btn btn-primary btn-xs"><i class="bx bx-chevron-left"></i> Mulai Acara</a>
                            <a href="{{ url('/admin/pj/register/acara/selesai') }}/{{$event->id}}" class="btn btn-danger btn-xs"><i class="bx bx-chevron-left"></i> Selesai</a>
                        </h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/pj/register/acara') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="card  h-100">
                                    <div class="card-body text-center">        
                                        <a href="#" class="btn-img-preview  mt-2 " data-img="{{ asset($event->img_event_path.$event->img_event_name) }}" data-bs-toggle="modal" data-bs-target="#modal-preview">
                                            <img src="{{ asset($event->img_event_path.$event->img_event_name) }}" class=" img-fluid">
                                        </a>
                                        <br><br><br>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="card h-100">
                                    <!-- table info -->
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover">

                                            <tbody>
                                                <tr>
                                                    <td>Nama</td>
                                                    <td>:</td>
                                                    <td>{{ $event->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Venue</td>
                                                    <td>:</td>
                                                    <td>{{ $event->venue }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Mulai</td>
                                                    <td>:</td>
                                                    <td>{{ $event->date_start }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Selesai</td>
                                                    <td>:</td>
                                                    <td>{{ $event->date_end }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Maksimal Jumlah Tamu</td>
                                                    <td>:</td>
                                                    <td>{{ $event->max_participant }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Penanggung Jawab</td>
                                                    <td>:</td>
                                                    <td>{{ $event->venue }}</td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        
                        <br>
                        <h5>
                            Guest Star
                        </h5>
                        <br>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Gambar</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_event_gs->count() > 0)
                                        @foreach($rs_event_gs as $index => $event_gs)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}.</td>
                                            <td width="25%">
                                                <a href="#" class="btn-img-preview  mt-2 " data-img="{{ asset($event_gs->img_path.$event_gs->img_name) }}" data-bs-toggle="modal" data-bs-target="#modal-preview">
                                                    <img src="{{ asset($event_gs->img_path.$event_gs->img_name) }}" class=" img-fluid" style="width:150px">
                                                </a>
                                            </td>
                                            <td>{{ $event_gs->name }}</td>
                                            <td>{{ $event_gs->description }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="8">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <br>
                        <h5>
                            Ticket
                        </h5>
                        <br>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Jenis Tiket</th>
                                        <th>Harga</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_event_ticket->count() > 0)
                                        @foreach($rs_event_ticket as $index => $event_ticket)
                                        <tr>
                                            <td class="text-center">{{ $index + 1}}.</td>
                                            <td>{{ $event_ticket->name }}</td>
                                            <td>{{ $event_ticket->harga }}</td>
                                            <td>{{ $event_ticket->description }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="8">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <br>
                        <h5>
                            Rundown
                        </h5>
                        <br>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Sesi</th>
                                        <th>Hari Ke</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_event_rundown->count() > 0)
                                        @foreach($rs_event_rundown as $index => $event_rundown)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}.</td>
                                            <td>{{ $event_rundown->name }}</td>
                                            <td>{{ $event_rundown->day }}</td>
                                            <td>{{ $event_rundown->start }}</td>
                                            <td>{{ $event_rundown->end }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="8">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
@endsection