@extends('admin.base.app')

@section('title')
    Sub Area
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Sub Area</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Sub Area</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/sub-area') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/validator/master/sub-area/edit_process') }}" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <input type="hidden" name="id" value="{{$sub_area->id}}" required>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label>Area <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="area_id" required>
                                            <option value="" selected disabled>Pilih</option>
                                            @foreach($rs_area as $area)
                                            <option value="{{$area->id}}" @if( old('area_id', $sub_area->area_id) == $area->id ) selected @endif>{{$area->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label >Sub Area <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $sub_area->name) }}" required>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label>Keterangan (optional)</label>
                                    <input type="text" class="form-control" name="description" value="{{ old('description',$sub_area->description) }}" >
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