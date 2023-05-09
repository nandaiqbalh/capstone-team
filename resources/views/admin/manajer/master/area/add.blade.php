@extends('admin.base.app')

@section('title')
    Area
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Area</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pendaftaran Area</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/area') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/validator/master/area/add_process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label>Lokasi <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="location_id" required>
                                            <option value="" selected disabled>Pilih</option>
                                            @foreach($rs_location as $location)
                                            <option value="{{$location->id}}" @if( old('location_id') == $location->id ) selected @endif>{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label >Area <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label>Keterangan (optional)</label>
                                    <input type="text" class="form-control" name="description" value="{{ old('description') }}" >
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label>Ronde </label>
                                        <select class="form-select" name="round_id">
                                            <option value="" selected disabled>Pilih</option>
                                            @foreach($rs_round as $round)
                                            <option value="{{$round->id}}" @if( old('round_id') == $round->id ) selected @endif>{{$round->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label></label>
                                    <input type="submit" class="form-control btn btn-primary" name="" value="Simpan" >
                                </div>
                            </div>
                            
                            <br>
                        </form>

                    </div>
                </div>
            </div>
@endsection