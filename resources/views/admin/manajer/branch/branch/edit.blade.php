@extends('admin.base.app')

@section('title')
    Cabang
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4">Cabang</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ubah Cabang</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/manajer/cabang') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <form action="{{ url('/admin/manajer/cabang/edit_process') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <input type="hidden" name="id" value="{{$branch->id}}" required>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Cabang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name',$branch->name) }}" required>
                                        <input type="hidden" class="form-control" name="old_name" value="{{ $branch->name }}"  required>
                                        <!-- <div id="emailHelp" class="form-text">Masukkan nama cabang.</div> -->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Alamat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" value="{{ old('address',$branch->address) }}" required>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nomor Telepon<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="no_telp" value="{{ old('no_telp',$branch->no_telp)}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nomor Rekening<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="no_rekening" value="{{ old('no_rekening',$branch->no_rekening)}}" required>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama Bank<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="bank_rekening" value="{{ old('bank_rekening',$branch->bank_rekening)}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama Pemilik Rekening<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="an_rekening" value="{{ old('an_rekening',$branch->an_rekening)}}" required>
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
                    // cek jiak province sudah ada nilai
                    if($('#province_id').val() != null) {
                        getCity();
                    }

                    // listen province change
                    $('#province_id').on('change', function(){
                        // remove city select option except first
                        $('#city_id').find('option').not(':first').remove();

                        getCity();
                    });

                    function getCity() {
                        // get list city
                        $.ajax({
                            url: '{{url("/admin/manajer/cabang/ajax-city-by-province")}}'+'/'+$('#province_id').val(),
                            cache: false,
                            method: "GET",
                            success: function(response) {
                                
                                // if not found
                                if(response.status == false) {
                                    console.log(response.message);
                                }
                                else {
                                    var data = response.data.rs_city;

                                    data.forEach(element => {
                                        // add to city select option
                                        if(element.id == '{{$branch->city_id}}') {
                                            $('#city_id').append(`<option value="${element.id}" selected>${element.name}</option>`);
                                        }
                                        else {
                                            $('#city_id').append(`<option value="${element.id}" >${element.name}</option>`);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            </script>
@endsection