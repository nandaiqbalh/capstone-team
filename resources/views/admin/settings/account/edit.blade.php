
@extends('admin.base.app')

@section('title')
    Pengaturan Profil Akun
@endsection

@section('content')
            <link rel="stylesheet" href="{{ asset('ijaboCropTool/ijaboCropTool.min.css') }}">
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Profil Akun</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- card-->
                {{-- <div class="card"> --}}
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card  h-100">
                                <div class="card-body text-center">        
                                    <a href="#" class="btn-img-preview  mt-2 " data-img="{{ asset($account->user_img_path.$account->user_img_name) }}" data-bs-toggle="modal" data-bs-target="#modal-preview">
                                        <img src="{{ asset($account->user_img_path.$account->user_img_name) }}" class="rounded-circle img-fluid" style="width: 60%;">
                                    </a>
                                    <br><br><br>
                                    <input type="file" class="form-control" id="user_img" name="user_img" >
                                    <small class="form-text text-muted">Format jpg/png, max 5 Mb.</small>
                                    <br>
                                    @if(empty($account->user_img_name))
                                    <label class="form-label" for="user_img">Pilih</label>
                                    @else
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                        <label class="form-label" for="user_img">{{  $account->user_img_name }}</label>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="card h-100">
                                <h5 class="card-header">Data Profil Akun</h5>
                                <form action="{{ url('/admin/settings/account/edit_process') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="card-body">
                                        {{ csrf_field()}}
                                        <input type="hidden" name="user_id" value="{{ $account->user_id }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Nama<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="user_name" value="{{ old('user_name',$account->user_name) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Nomor Telepon<span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="no_telp" value="{{ old('no_telp',$account->no_telp) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Email<span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="user_email" value="{{ old('user_email',$account->user_email) }}" required readonly disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>ID Pengguna<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="id_pengguna" value="{{ old('id_pengguna',$account->nik) }}" minlength="6" maxlength="11" pattern="[0-9]+" required readonly disabled>
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
                        
                    </div>
                {{-- </div> --}}
                <br>
                <!-- card-->
                <div class="card">
                    <h5 class="card-header">Ubah Password</h5>
                    <form action="{{ url('/admin/settings/account/edit_password') }}" method="post" autocomplete="off">
                        <div class="card-body">
                            {{ csrf_field()}}
                            <input type="hidden" name="user_id" value="{{ $account->user_id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Password Saat Ini<span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Password Baru<span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="new_password" required>
                                        <small class="form-text text-muted">Minimal 8 karakter, minimal mengandung angka dan huruf kapital.</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Ulangi Password Baru<span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="repeat_new_password" required>
                                        <small class="form-text text-muted">Minimal 8 karakter, minimal mengandung angka dan huruf kapital.</small>
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
            <script src="{{ asset('ijaboCropTool/ijaboCropTool.min.js') }}"></script>
            <script>
                $('#user_img').ijaboCropTool({
                   preview : '.image-previewer',
                   setRatio:1,
                   allowedExtensions: ['jpg', 'jpeg','png'],
                   buttonsText:['CROP','QUIT'],
                   buttonsColor:['#30bf7d','#ee5155', -15],
                   processUrl:'{{ route("crop") }}',
                   withCSRF:['_token','{{ csrf_token() }}'],
                   onSuccess:function(message, element, status){
                      alert(message);
                      location.reload();
                   },
                   onError:function(message, element, status){
                     alert(message);
                   }
                   
                });
           </script>  
    
@endsection