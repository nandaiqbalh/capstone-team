
@extends('admin.base.app')

@section('title')
    Acara
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Registrasi /</span>Detail Acara</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tambah Detail Acara</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/pj/register/acara') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
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
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td>{{ $event->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Venue</td>
                                        <td>:</td>
                                        <td>{{ $event->venue }}</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <h5>
                            Guest Star
                        </h5>
                        <br>
                        <form action="{{ url('/admin/pj/register/acara/add-detail-process-guest') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                            {{ csrf_field()}}
                            <input type="hidden" class="form-control" name="event_id" value="{{ $event->id }}" required>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Nama<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Upload Gambar Guest Star<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="guest_star_img" value="{{ old('guest_star_img') }}" required>
                                    </div>
                                </div>
                                 <div class="mb-3">
                                        <label >Keterangan<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="description" value="{{ old('description') }}" required>
                                    </div>
                                
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Tambahkan</button>
                        </form>
                        <br>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Gambar</th>
                                        <th>Nama</th>
                                        <th>Keterangan</th>
                                        <th width="10%">Tindakan</th>
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
                                            <td class="text-center">
                                                <a href="{{ url('/admin/pj/register/acara/delete-guest-process') }}/{{ $event_gs->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $event_gs->name }} ?')"> Hapus</a>
                                            </td>
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
                        <form action="{{ url('/admin/pj/register/acara/add-detail-process-ticket') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                            {{ csrf_field()}}
                            <input type="hidden" class="form-control" name="event_id" value="{{ $event->id }}" required>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Jenis Tiket<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Harga<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="harga" value="{{ old('harga') }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                        <label >Keterangan<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="description" value="{{ old('description') }}" required>
                                    </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Tambahkan</button>
                        </form>
                        <br>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Jenis Tiket</th>
                                        <th>Harga</th>
                                        <th>Keterangan</th>
                                        <th width="10%">Tindakan</th>
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
                                            <td class="text-center">
                                                <a href="{{ url('/admin/pj/register/acara/delete-ticket-process') }}/{{ $event_ticket->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $event_ticket->name }} ?')"> Hapus</a>
                                            </td>
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
                        <form action="{{ url('/admin/pj/register/acara/add-detail-process-rundown') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                            {{ csrf_field()}}
                            <input type="hidden" class="form-control" name="event_id" value="{{ $event->id }}" required>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label > Nama Sesi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" value="{{ old('harga') }}" placeholder="Contoh : Pembukaan" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Hari Ke<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="day" value="{{ old('day') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Waktu Mulai<span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="start" value="{{ old('start') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Waktu Selesai<span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="end" value="{{ old('end') }}" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Tambahkan</button>
                        </form>
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
                                        <th width="10%">Tindakan</th>
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
                                            <td class="text-center">
                                                <a href="{{ url('/admin/pj/register/acara/delete-rundown-process') }}/{{ $event_rundown->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $event_rundown->name }} ?')"> Hapus</a>
                                            </td>
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