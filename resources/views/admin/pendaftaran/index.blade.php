@extends('admin.base.app')

@section('title')
Pendaftaran
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4">Pendaftaran</h5>
    <!-- notification -->
    @include("template.notification")

    <!-- Bordered Table -->
    <div class="card">
        <h5 class="card-header">Data Pendaftaran</h5>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="form-inline" action="{{ url('/admin/mahasiswa/search') }}" method="get" autocomplete="off">
                        <div class="row">
                            <div class="col-auto mt-1">
                                <input class="form-control mr-sm-2" type="search" name="nama" value="{{ !empty($nama) ? $nama : '' }}" placeholder="Nama" minlength="1" required>
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
                <div class="col-md-6">
                    <form class="form-inline" action="{{ url('/admin/pendaftaran/add') }}" method="get" autocomplete="off">
                        <div class="row">
                            <div class="col-auto mt-1">
                                
                                    <select class="form-select" name="id_topik" required>
                                        <option value="" disabled selected>-- Pilih --</option>
                                        @foreach ($rs_topik as $topik)
                                            <option value="{{$topik->id}}" @if( old('id_topik') == '{{$topik->id}}' ) selected @endif>{{$topik->nama}}</option>
                                        @endforeach
                                    </select>
                               
                            </div>
                            <div class="col-auto mt-1">
                                <button class="btn btn-outline-secondary ml-1" type="submit">
                                    <i class="bx bx-plus"></i>
                                </button>
                            
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <br>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Topik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rs_pendaftaran->count() > 0)
                        @foreach($rs_pendaftaran as $index => $pendaftaran)
                        <tr>
                            <td class="text-center">{{ $index + $rs_pendaftaran->firstItem() }}.</td>
                            <td>{{ $pendaftaran->user_name }}</td>
                            <td>{{ $pendaftaran->nomor_induk }}</td>
                            <td>{{ $pendaftaran->nama_topik }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="4">Tidak ada data.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- pagination -->
            <div class="row mt-3 justify-content-between">
                <div class="col-auto mr-auto">
                    <p>Menampilkan {{ $rs_pendaftaran->count() }} dari total {{ $rs_pendaftaran->total() }} data.</p>
                </div>
                <div class="col-auto ">
                    {{ $rs_pendaftaran->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection