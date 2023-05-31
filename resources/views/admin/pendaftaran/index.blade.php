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
                            <th>Siklus</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Angkatan</th>
                            <th>Topik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rs_pendaftaran->count() > 0)
                        @foreach($rs_pendaftaran as $index => $pendaftaran)
                        <tr>
                            <td class="text-center">{{ $index + $rs_pendaftaran->firstItem() }}.</td>
                            <td>{{ $pendaftaran->tahun_ajaran }}</td>
                            <td>{{ $pendaftaran->user_name }}</td>
                            <td>{{ $pendaftaran->nomor_induk }}</td>
                            <td>{{ $pendaftaran->angkatan }}</td>
                            @if ($pendaftaran->nama_topik == null)
                            <td style="text-align: center">
                                <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal{{$pendaftaran->user_id}}">
                                Pilih Topik
                                </button>
                            </td>
                            @else
                            <td>{{ $pendaftaran->nama_topik }}</td>
                            @endif
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{$pendaftaran->user_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Pilih Topik Untuk {{$pendaftaran->user_name}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="form-inline" action="{{ url('/admin/pendaftaran/update-mahasiswa-topik') }}" method="get" autocomplete="off">
                                        <input type="hidden" name="user_id" value="{{$pendaftaran->user_id}}">
                                        <div class="table-responsive text-nowrap">
                                        <select class="form-select" name="id_topik" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_topik_prioritas as $topik_prioritas)
                                                @if ($topik_prioritas->id_mahasiswa == $pendaftaran->user_id)
                                                <option value="{{$topik_prioritas->id_topik}}" @if( old('id_topik') == '{{$topik_prioritas->id_topik}}' ) selected @endif>{{$topik_prioritas->nama_topik}} Prioritas {{$topik_prioritas->prioritas}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <br>
                                        <p>Topik Prioritas</p>
                                        <ul class="list-group">
                                            @foreach ($rs_topik_prioritas as $index => $topik_prioritas)
                                            @if ($topik_prioritas->id_mahasiswa == $pendaftaran->user_id)
                                            <li class="list-group-item">{{ $topik_prioritas->nama_topik }} | Prioritas Ke {{ $topik_prioritas->prioritas }}</li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                            </div>
                            </div>
                        </div>
                        </div>
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