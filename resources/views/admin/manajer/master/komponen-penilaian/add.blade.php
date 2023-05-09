@extends('admin.base.app')

@section('title')
    Komponen Penilaian
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Komponen Penilaian</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- CUSTOM ALERT FOR AJAX -->
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </symbol>
                    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                    </symbol>
                    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </symbol>
                </svg>

                <div id="alert-success-custom" class="alert alert-success d-flex align-items-center alert-auto-close d-none" role="alert" >
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                    <div id="alert-success-custom-message" >
                    </div>
                </div>

                <div id="alert-danger-custom" class="alert alert-danger d-flex align-items-center alert-auto-close d-none" role="alert" >
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div id="alert-danger-custom-message">
                    </div>
                </div>

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pendaftaran Komponen Penilaian</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/validator/master/komponen-penilaian') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form id="add-komponen-penilaian-form" action="#" method="post" autocomplete="off">
                            {{ csrf_field()}}
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label>Item <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" id="items_id" name="items_id" required>
                                            <option value="" selected disabled>Pilih</option>
                                            @foreach($rs_items as $items)
                                            <option value="{{$items->id}}" @if( old('items_id') == $items->id ) selected @endif>{{$items->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label >Komponen Penilaian <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label>Parameter <span class="text-danger">*</span></label>
                                        <select class="form-select" id="parameter_true" name="parameter_true" required>
                                            <option value="" selected disabled>Pilih</option>
                                            <option value="Aman" @if( old('parameter_true') == 'Aman' ) selected @endif>Aman</option>
                                            <option value="Bersih" @if( old('parameter_true') == 'Bersih' ) selected @endif>Bersih</option>
                                            <option value="Rapih" @if( old('parameter_true') == 'Rapih' ) selected @endif>Rapih</option>
                                            <option value="Tampak Baru" @if( old('parameter_true') == 'Tampak Baru' ) selected @endif>Tampak Baru</option>
                                            <option value="Ramah Lingkungan" @if( old('parameter_true') == 'Ramah Lingkungan' ) selected @endif>Ramah Lingkungan</option>
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

            <script>
                $(document).ready(function(){

                    $('#add-komponen-penilaian-form').on('submit', function(e) {
                        e.preventDefault();
                        addKomponenPenilaian();
                    });

                    // add komponen penilain
                    function addKomponenPenilaian() {
                        var _token =  $("input[name=_token]").val();
    
                        $.ajax({
                            url: '{{url("/admin/validator/master/komponen-penilaian/ajax_add_process")}}',
                            cache: false,
                            method: "POST",
                            data: {
                                _token:_token,
                                items_id: $('#items_id').find(":selected").val(),
                                name : $("input[name=name]").val(),
                                parameter_true : $('#parameter_true').find(":selected").val()
                            },
                            success: function(response) {

                                // if not found
                                if(response.status == true) {
                                    // reset form input
                                    $("input[name=name]").val('');
                                    $('#parameter_true').prop('selectedIndex',0);

                                    $('#alert-success-custom').removeClass('d-none');
                                    $('#alert-success-custom-message').html(response.message);
    
                                    // auto close alert
                                    window.setTimeout(function() {
                                        $('#alert-success-custom').addClass('d-none');
                                    },2000);
                                }
                                else {
                                    $('#alert-danger-custom').removeClass('d-none');
                                    $('#alert-danger-custom-message').html(response.message);
    
                                    // auto close alert
                                    window.setTimeout(function() {
                                        $('#alert-danger-custom').addClass('d-none');
                                    },2000);
                                    
                                }

                            }
                        });
                    }
                });
            </script>
@endsection