@extends('admin.base.app')

@section('title')
    Fasilitas
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Registrasi /</span> Fasilitas</h5>
                <!-- notification -->
                @include("template.notification")
                
                <div class="alert alert-success d-none" id="success-alert" role="alert">
                    Data Fasilitas Berhasil Disimpan!
                </div>
                <div class="alert alert-danger d-none" id="error-alert" role="alert">
                    Jumlah Fasilitas Harus dibawah 50
                </div>
                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pendaftaran Fasilitas</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/pj/register/aset') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <form id='form_item' autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            

                            

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Aset<span class="text-danger">*</span></label>
                                        <select class="form-select select-2" id="item_id" name="item_id" required>
                                            <option value="" selected disabled>Pilih</option>
                                            @foreach($rs_item as $item)
                                            <option value="{{$item->id}}" @if( old('item_id') == $item->id ) selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Jumlah<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="qty" value="{{ old('qty')}}" required>
                                    </div>
                                </div>
                            </div>

                            <br>
                        </div>
                        <div class="card-footer float-end">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    // listen Location change
                    $('#location_id').on('change', function(){
                        // remove Area select option except first
                        $('#area_id').find('option').not(':first').remove();
                        $('#sub_area_id').find('option').not(':first').remove();

                        // get list Area
                        $.ajax({
                            url: "{{url('/admin/pj/register/aset/ajax-add-item-area')}}"+'/'+$('#location_id').val(),
                            cache: false,
                            method: "GET",
                            success: function(response) {
                                
                                // if not found
                                if(response.status == false) {
                                    console.log(response.message);
                                }
                                else {
                                    var data = response.data.rs_area;

                                    data.forEach(element => {
                                        // add to Area select option
                                        $('#area_id').append(`<option value="${element.id}">${element.name}</option>`);
                                    });
                                }
                            }
                        });
                    });
                    $('#area_id').on('change', function(){
                        // remove SubAra select option except first
                        $('#sub_area_id').find('option').not(':first').remove();

                        // get list SubArea
                        $.ajax({
                            url: "{{url('/admin/pj/register/aset/ajax-add-item-sub-area')}}"+'/'+$('#area_id').val(),
                            cache: false,
                            method: "GET",
                            success: function(response) {
                                
                                // if not found
                                if(response.status == false) {
                                    console.log(response.message);
                                }
                                else {
                                    var data = response.data.rs_sub_area;

                                    data.forEach(element => {
                                        // add to subArea select option
                                        $('#sub_area_id').append(`<option value="${element.id}">${element.name}</option>`);
                                    });
                                }
                            }
                        });
                    });
                });

                // add data
                 $('#form_item').on('submit', function(event){
                    event.preventDefault();
                    addItem();
                    console.log();
                 })
                var url = "{{ url('admin/pj/register/aset/ajax-add-item-process') }}";
                function addItem() {
                    var _token =  $("input[name=_token]").val();
                    $.ajax({
                        url: url,
                        cache: false,
                        method: "POST",
                        data: {
                            _token : _token,
                            location_id : $('#location_id').find(":selected").val(),
                            area_id : $('#area_id').find(":selected").val(),
                            sub_area_id : $('#sub_area_id').find(":selected").val(),
                            item_id : $('#item_id').find(":selected").val(),
                            zona : $('#zona').find(":selected").val(),
                            qty : $("input[name=qty]").val(),
                        },
                        success: function(response) {
                            console.log(response);
                            // if not found
                            if(response.status == true) {
                                $('#success-alert').removeClass('d-none');
                                // auto close alert
                                window.setTimeout(function() {
                                    $('#success-alert').addClass('d-none');
                                },5000); 
                            }
                            else{
                                $('#error-alert').removeClass('d-none');
                                // auto close alert
                                window.setTimeout(function() {
                                    $('#error-alert').addClass('d-none');
                                },5000);
                            }

                        }
                    });
                }

            </script>
@endsection