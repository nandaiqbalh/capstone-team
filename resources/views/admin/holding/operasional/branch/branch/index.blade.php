@extends('admin.base.app')

@section('title')
    Rumah Sakit
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"> Rumah Sakit</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header"> Rumah Sakit</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/holding-operasional/rumah-sakit/search') }}" method="get" autocomplete="off">
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
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th widTH="20%">Rumah Sakit</th>
                                        <th width="15%">Regional</th>
                                        <th width="20%">Kota</th>
                                        <th width="10%">Kelas</th>
                                        <th width="18%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="align-baseline">
                                    @if($rs_branch->count() > 0)
                                        @foreach($rs_branch as $index => $branch)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_branch->firstItem() }}</td>
                                            <td>{{ $branch->name }}</td>
                                            <td>{{ $branch->region_name }}</td>
                                            
                                            <td >{{ $branch->city_name }}</td>
                                            <td class="text-center">{{ $branch->class }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-outline-secondary btn-xs m-1 " data-bs-toggle="modal" data-bs-target="#detailModal{{ $branch->id }}">Detail</a>
                                                
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
                                                                                <td width="20%">ID</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{$branch->id_branch}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="20%">Tipe</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{ucwords($branch->type)}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="20%">Nama</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{$branch->name}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="20%">Kelas</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{$branch->class}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="20%">Regional</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{$branch->region_name}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="20%">Provinsi</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{$branch->province_name}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="20%">Kota/Kabupaten</td>
                                                                                <td width="5%">:</td>
                                                                                <td>{{$branch->city_name}}</td>
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
                        
                    </div>
                </div>
            </div>
    
@endsection