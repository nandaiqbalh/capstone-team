@extends('admin.base.app')

@section('title')
    Lokasi
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Lokasi</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Lokasi</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/lokasi') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/validator/master/lokasi/edit_process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <input type="hidden" name="id" value="{{$location->id}}" required>
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label >Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $location->name) }}" required>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label>Keterangan (optional)</label>
                                    <input type="text" class="form-control" name="description" value="{{ old('description',$location->description) }}" >
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