@extends('admin.base.app')

@section('title')
Item Penilaian
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Registrasi /</span> Item Penilaian</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Item Penilaian</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/checker/register/item-penilaian') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/checker/register/item-penilaian/edit-process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label>Nama Sub Area <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="sub_area_id" required>
                                            <option value="" selected disabled>Pilih</option>
                                            @foreach($rs_sub_area_branch as $sub_area)
                                            <option value="{{$sub_area->sub_area_id}}" @if( old('sub_area_id', $sub_area->sub_area_id) == $item->sub_area_id ) selected @endif>{{$sub_area->nama_sub_area}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label>Item Penilaian <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="item_id" disabled>
                                            <option value="{{$item->id}}" selected disabled>{{$item->nama_item}}#{{$item->unique_id}}</option>
                                            {{-- @foreach($rs_sub_area as $sub_area)
                                            <option value="{{$sub_area->items_id}}" @if( old('item_id', $sub_area->items_id) == $sub_area->items_id ) selected @endif>{{$sub_area->nama_item}}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
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