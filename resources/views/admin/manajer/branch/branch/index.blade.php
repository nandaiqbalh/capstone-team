@extends('admin.base.app')

@section('title')
    Cabang
@endsection

@section('content')
<!-- dropzone js -->
<link rel="stylesheet" href="{{asset('/vendor/libs/dropzone/dropzone593.min.css')}}" type="text/css" />
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"> Cabang</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header"> Cabang</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/manajer/cabang/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-auto mt-1">
                                            <input class="form-control mr-sm-2" type="search" name="search_string" value="{{ !empty($search_string) ? $search_string : '' }}" placeholder="Cari ..." minlength="3" required>
                                        </div>
                                        <div class="col-auto mt-1">
                                            <button class="btn btn-outline-secondary ml-1" type="submit" name="action" value="search">
                                                <i class="bx bx-search-alt-2"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary ml-1" type="submit" name="action" value="reset">
                                                <i class="bx bx-reset"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row justify-content-end mb-2">
                            <div class="col-auto ">
                                <a href="{{ url('/admin/manajer/cabang/add') }}" class="btn btn-primary btn-xs float-right"><i class="bx bx-plus"></i> Tambah</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th widTH="20%">Cabang</th>
                                        {{-- <th width="15%">Regional</th> --}}
                                        <th width="20%">Kota</th>
                                        {{-- <th width="10%">Kelas</th> --}}
                                        <th width="18%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="align-baseline">
                                    @if($rs_branch->count() > 0)
                                        @foreach($rs_branch as $index => $branch)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_branch->firstItem() }}</td>
                                            <td>{{ $branch->name }}</td>
                                            {{-- <td>{{ $branch->region_name }}</td> --}}
                                            
                                            <td >{{ $branch->address }}</td>
                                            {{-- <td class="text-center">{{ $branch->class }}</td> --}}
                                            <td class="text-center">
                                                <a href="#" class="btn btn-outline-secondary btn-xs m-1 " data-bs-toggle="modal" data-bs-target="#detailModal{{ $branch->id }}">Detail</a>
                                                @if($role_id != '06')
                                                <a href="{{ url('/admin/manajer/cabang/akun') }}/{{ $branch->id }}" class="btn btn-outline-secondary btn-xs m-1 ">Akun</a>
                                                <br>
                                                <a href="{{ url('/admin/manajer/cabang/edit') }}/{{ $branch->id }}" class="btn btn-outline-warning btn-xs float-right">Ubah</a>
                                                <a href="{{ url('/admin/manajer/cabang/delete_process') }}/{{ $branch->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
                                
                                                @endif
                                                
                                                <!-- Modal Detail -->
                                                <div class="modal fade" id="detailModal{{ $branch->id }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                            <div class="modal-header" >
                                                                <div></div>
                                                                <div class="text-center">
                                                                    <h5 class="modal-title" id="detailModal{{ $branch->id }}Label" >Detail {{ $branch->name }}</h5>
                                                                </div>
                                                                <div>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- table -->
                                                                <div class="table-responsive">
                                                                    <table class="table table-borderless">
                                                                        <tbody class="align-baseline" style="text-align: left;">
                                                                            <tr>
                                                                                <td width="20%">Nama</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{$branch->name}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="20%">Alamat</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{$branch->address}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="20%">Nomor Telepon</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{$branch->no_telp}}</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <br>
                                                                <a href="{{ url('/admin/manajer/cabang/item-cabang') }}/{{$branch->id}}" class="btn btn-outline-primary float-end">Lihat Fasilitas</a>
                                                                <br>
                                                                <br>
                                                                <p>
                                                                    <strong>Akun</strong>
                                                                </p>
                                                                <div class="table-responsive text-nowrap">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr class="text-center">
                                                                                <th width="5%">No</th>
                                                                                <th>Nama</th>
                                                                                <th>Tipe</th>
                                                                                <th width="10%">Tindakan</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody style="text-align: left;">
                                                                            @if($rs_user[$branch->id]->count() > 0)
                                                                                @foreach($rs_user[$branch->id] as $index => $user)
                                                                                <tr>
                                                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                                                    <td>{{ $user->user_name }}</td>
                                                                                    <td>{{ $user->role_name }}</td>
                                                                                    <td>
                                                                                        @if($user->role_id == '02' && $role_id != '06')
                                                                                        <a href="{{ url('/admin/manajer/cabang/akun/edit') }}/{{ $user->user_id }}" class="btn btn-outline-warning btn-xs float-right">Ubah</a>
                                                                                        <a href="{{ url('/admin/manajer/cabang/akun/delete_process') }}/{{ $user->user_id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
                                                                        
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            @else
                                                                                <tr class="text-center">
                                                                                    <td colspan="4">Tidak ada data.</td>
                                                                                </tr>
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <br>
                                                                <br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="6">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto ">
                                <p>Menampilkan {{ $rs_branch->count() }} dari total {{ $rs_branch->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_branch->links() }}
                            </div>
                        </div>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="uploadExcelModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="uploadExcelModalLabel">Impor Data Cabang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
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

                                        <div id="alert-success-custom" class="alert alert-success d-flex align-items-center  d-none" role="alert" >
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                            <div id="alert-success-custom-message" >
                                            </div>
                                        </div>

                                        <div id="alert-danger-custom" class="alert alert-danger d-flex align-items-center  d-none" role="alert" >
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                            <div id="alert-danger-custom-message">
                                            </div>
                                        </div>
                                        <br>
                                        
                                        <form action="{{ url('/admin/manajer/cabang/import_data') }}" class="dropzone" id="my-dropzone" method="post" autocomplete="off" enctype="multipart/form-data">
                                            {{ csrf_field()}}
                                        </form>
                                        <div class="form-text">*upload file excel berformat .xlsx</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- dropzone js -->
     <script src="{{asset('/vendor/libs/dropzone/dropzone593.min.js')}}"></script>
     <script>
        Dropzone.options.myDropzone = { // camelized version of the `id`
            paramName: "excel_file", // The name that will be used to transfer the file
            maxFilesize: 10, // MB
            maxFiles: 1,
            uploadMultiple: false,
            acceptedFiles: ".xlsx",
            uploadMultiple: false,
            init: function() {
                // handle callback 
                this.on("complete", function(response) {
                    var dataResponse = JSON.parse(response.xhr.response);

                    if(dataResponse.hasOwnProperty('errors')){
                        showError(dataResponse.message);
                    }

                    if(dataResponse.status == true){
                        showSuccess(dataResponse.message);
                    }
                    else {
                        showError(dataResponse.message);
                    }
                });
            }
        };

        $('.dropzone').css('border-style','dashed');

        function showError(message){
            $('#alert-danger-custom').removeClass('d-none');
            $('#alert-danger-custom-message').html(message);

            // auto close alert
            window.setTimeout(function() {
                $('#alert-danger-custom').addClass('d-none');
            },4000);
        }

        function showSuccess(message){
            $('#alert-success-custom').removeClass('d-none');
            $('#alert-success-custom-message').html(message);

            // auto close alert
            window.setTimeout(function() {
                $('#alert-success-custom').addClass('d-none');
            },4000);
        }

    </script>
@endsection