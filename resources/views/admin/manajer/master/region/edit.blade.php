@extends('admin.base.app')

@section('title')
    Regional
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Regional</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Regional</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/region') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/validator/master/region/edit_process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <input type="hidden" name="id" value="{{$region->id}}" required>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label >Regional <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $region->name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label >Direktur Regional <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="direg_name" value="{{ old('direg_name',$region->direg_name) }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Keterangan (optional)</label>
                                    <input type="text" class="form-control" name="description" value="{{ old('description',$region->description) }}" >
                                </div>
                                
                            </div>
                        </div>
                        <div class="card-footer float-end">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
@endsection