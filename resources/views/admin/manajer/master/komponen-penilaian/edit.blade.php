@extends('admin.base.app')

@section('title')
    Komponen Penilaian
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Komponen Penilaian</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Komponen Penilaian</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/komponen-penilaian') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/admin/validator/master/komponen-penilaian/edit_process')}}" method="post" autocomplete="off">
                            {{ csrf_field()}}

                            <input type="hidden" name="id" value="{{$item_components->id}}">
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label >Komponen Penilaian <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $item_components->name) }}" required>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label>Parameter <span class="text-danger">*</span></label>
                                        <select class="form-select" id="parameter_true" name="parameter_true" required>
                                            <option value="" selected disabled>Pilih</option>
                                            <option value="Aman" @if( old('parameter_true', $item_components->parameter_true) == 'Aman' ) selected @endif>Aman</option>
                                            <option value="Bersih" @if( old('parameter_true', $item_components->parameter_true) == 'Bersih' ) selected @endif>Bersih</option>
                                            <option value="Rapih" @if( old('parameter_true', $item_components->parameter_true) == 'Rapih' ) selected @endif>Rapih</option>
                                            <option value="Tampak Baru" @if( old('parameter_true', $item_components->parameter_true) == 'Tampak Baru' ) selected @endif>Tampak Baru</option>
                                            <option value="Ramah Lingkungan" @if( old('parameter_true', $item_components->parameter_true) == 'Ramah Lingkungan' ) selected @endif>Ramah Lingkungan</option>
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