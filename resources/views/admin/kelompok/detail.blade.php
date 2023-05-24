
@extends('admin.base.app')

@section('title')
    Mahasiswa
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Contoh Halaman</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Mahasiswa</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/mahasiswa') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                         <!-- table info -->
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="20%"></th>
                                        <th width="5%"></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Nomor</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                    </tr>
                                    <tr>
                                        <td>Judul TA</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->judul_ta }}</td>
                                    </tr>
                                    <tr>
                                        <td>Topik</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->nama_topik }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->status_kelompok }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row justify-content-end mb-2">
                            <div class="col-auto ">
                                <button type="button" class="btn btn-primary btn-xs float-right" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Tambah Mahasiswa
                                </button>
                            </div>
                        </div>
                        <h6>List Mahasiswa</h6>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Nama</th>
                                        <th>NIM</th>
                                        <th width="18%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_mahasiswa->count() > 0)
                                    @foreach($rs_mahasiswa as $index => $mahasiswa)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $mahasiswa->user_name }}</td>
                                        <td>{{ $mahasiswa->nomor_induk }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/admin/mahasiswa/detail') }}/{{ $mahasiswa->user_id }}" class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                            <a href="{{ url('/admin/kelompok/delete-mahasiswa-process') }}/{{ $mahasiswa->user_id }}/{{ $kelompok->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $mahasiswa->user_name }} ?')"> Hapus</a>
                                        </td>
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
                        <br>
                        <h6 class="mb-0">List Dosen Pembimbing</h6>
                        <br>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Nama Dosbing</th>
                                        <th>NIP/NIDN</th>
                                        <th>Posisi</th>
                                        <th>Status Persetujuan</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_dosbing->count() > 0)
                                    @foreach($rs_dosbing as $index => $dosbing)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $dosbing->user_name }}</td>
                                        <td>{{ $dosbing->nomor_induk }}</td>
                                        <td>{{ $dosbing->status_dosen }}</td>
                                        <td>{{ $dosbing->status_persetujuan }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/admin/dosen/detail') }}/{{ $dosbing->user_id }}" class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                            <a href="{{ url('/admin/kelompok/delete-dosen-process') }}/{{ $dosbing->user_id }}/{{ $kelompok->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $dosbing->user_name }} ?')"> Hapus</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td class="text-center" colspan="5">Tidak ada data.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Mahasiswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ url('/admin/kelompok/add-mahasiswa-kelompok') }}" method="get" autocomplete="off">
        <input type="hidden" name="id_kelompok" value="{{$kelompok->id}}">
        <select class="form-select" name="id_mahasiswa_nokel" required>
            <option value="" disabled selected>-- Pilih --</option>
            @foreach ($rs_mahasiswa_nokel as $mahasiswa_nokel)
                <option value="{{$mahasiswa_nokel->user_id}}" @if( old('id_mahasiswa_nokel') == '{{$mahasiswa_nokel->user_id}}' ) selected @endif>{{$mahasiswa_nokel->user_name}}</option>
            @endforeach
        </select>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection