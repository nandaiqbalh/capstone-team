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
                    <h5 class="card-header">Regional</h5>

                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/validator/master/region/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-auto mt-1">
                                            <input class="form-control mr-sm-2" type="search" name="query_all" value="{{ !empty($query_all) ? $query_all : '' }}" placeholder="Cari ..." minlength="1" required>
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
                                @if($role_id != '06')
                                
                                <a href="{{ url('/admin/validator/master/region/add') }}" class="btn btn-primary btn-xs float-right"><i class="bx bx-plus"></i> Tambah</a>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Regional</th>
                                        <th width="35%">Direktur Regional</th>
                                        <th width="18%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_region->count() > 0)
                                        @foreach($rs_region as $index => $region)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_region->firstItem() }}</td>
                                            <td>{{ $region->name }}</td>
                                            <td>{{ $region->direg_name }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/admin/validator/master/region/detail') }}/{{ $region->id }}" class="btn btn-outline-secondary btn-xs m-1 ">Detail</a>
                                                @if($role_id != '06')
                                                <a href="{{ url('/admin/validator/master/region/edit') }}/{{ $region->id }}" class="btn btn-outline-warning btn-xs float-right">Ubah</a>
                                                <a href="{{ url('/admin/validator/master/region/delete_process') }}/{{ $region->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus?')">Hapus</a>
                                
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
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto ">
                                <p>Menampilkan {{ $rs_region->count() }} dari total {{ $rs_region->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_region->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
@endsection