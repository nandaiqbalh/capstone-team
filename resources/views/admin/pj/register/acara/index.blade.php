@extends('admin.base.app')

@section('title')
Acara 
@endsection

@section('content')
    
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Registrasi /</span> Acara </h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <h5 class="card-header">Daftar Acara</h5>
                    
                    <div class="card-body">    
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <form class="form-inline" action="{{ url('/admin/pj/register/acara/search') }}" method="get" autocomplete="off">
                                    <div class="row">
                                        <div class="col-auto mt-1">
                                            <input class="form-control mr-sm-2" type="search" name="search" value="{{ !empty($search) ? $search : '' }}" placeholder="Cari ..." minlength="1" >
                                            
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
                        <br>
                        <div class="row justify-content-end mb-2">
                            <div class="col-auto ">
                                <a href="{{ url('/admin/pj/register/acara/add') }}" class="btn btn-primary btn-xs float-end m-1"><i class="bx bx-plus"></i> Tambah</a>
                            </div>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Acara</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_branch_event->count() > 0)
                                        @foreach($rs_branch_event as $index => $branch_event)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_branch_event->firstItem() }}.</td>
                                            <td>{{ $branch_event->name }}</td>
                                            <td>{{ $branch_event->venue }}</td>
                                            <td>{{ $branch_event->status }}</td>
                                            <td>{{ $branch_event->date_start }}</td>
                                            <td>{{ $branch_event->date_end }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/admin/pj/register/acara/detail') }}/{{ $branch_event->id }}" class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                                <a href="{{ url('/admin/pj/register/acara/edit') }}/{{ $branch_event->id }}" class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                                <a href="{{ url('/admin/pj/register/acara/delete-process') }}/{{ $branch_event->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $branch_event->name }} ?')"> Hapus</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="8">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto mr-auto">
                                <p>Menampilkan {{ $rs_branch_event->count() }} dari total {{ $rs_branch_event->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_branch_event->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
@endsection