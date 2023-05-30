@extends('admin.base.app')

@section('title')
Broadcast
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"> Broadcast</h5>
    <!-- notification -->
    @include("template.notification")

    <!-- Bordered Table -->
    <div class="card">
        <h5 class="card-header">Data Broadcast</h5>

        <div class="card-body">
            
            <br>
            <div class="row justify-content-end mb-2">
                <div class="col-auto ">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah Data
                    </button>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama Event</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th width="18%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rs_broadcast->count() > 0)
                        @foreach($rs_broadcast as $index => $broadcast)
                        <tr>
                            <td class="text-center">{{ $index + $rs_broadcast->firstItem() }}.</td>
                            <td>{{ $broadcast->nama_event }}</td>
                            <td>{{ $broadcast->tgl_mulai }}</td>
                            <td>{{ $broadcast->tgl_selesai }}</td>
                            <td class="text-center">
                                <a href="{{ url('/admin/broadcast/detail') }}/{{ $broadcast->id }}" class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                <button type="button" class="btn btn-outline-warning btn-xs m-1" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $broadcast->id }}"> Ubah</button>
                                <a href="{{ url('/admin/broadcast/delete-process') }}/{{ $broadcast->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $broadcast->id }} ?')"> Hapus</a>
                            </td>
                        </tr>
                        {{-- modal edit --}}
                        <div class="modal fade" id="exampleModal{{ $broadcast->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Edit Broadcast</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('/admin/broadcast/edit-process') }}" method="post" autocomplete="off">
                                        <input type="hidden" name="id" value="{{ $broadcast->id }}">
                                        {{ csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label >Nama Event<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="nama_event" value="{{ old('nama_event',$broadcast->nama_event) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label >Keterangan<span class="text-danger"></span></label>
                                                    <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan',$broadcast->keterangan) }}">
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Tanggal Mulai<span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="tgl_mulai" value="{{ old('tgl_mulai',$broadcast->tgl_mulai) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label >Tanggal Selesai<span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="tgl_selesai" value="{{ old('tgl_selesai',$broadcast->tgl_selesai) }}" required>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label >Link Pendukung</label>
                                                    <input type="text" class="form-control" name="link_pendukung" value="{{ old('link_pendukung',$broadcast->link_pendukung) }}" >
                                                </div>
                                            </div>
                                        </div>                            
                                        <br>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
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
                    <p>Menampilkan {{ $rs_broadcast->count() }} dari total {{ $rs_broadcast->total() }} data.</p>
                </div>
                <div class="col-auto ">
                    {{ $rs_broadcast->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Broadcast</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ url('/admin/broadcast/add-process') }}" method="post" autocomplete="off">
                {{ csrf_field()}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label >Nama Event<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_event" value="{{ old('nama_event') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label >Keterangan<span class="text-danger"></span></label>
                            <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}">
                        </div>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Tanggal Mulai<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tgl_mulai" value="{{ old('tgl_mulai') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label >Tanggal Selesai<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tgl_selesai" value="{{ old('tgl_selesai') }}" required>
                        </div>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label >Link Pendukung</label>
                            <input type="text" class="form-control" name="link_pendukung" value="{{ old('link_pendukung') }}" >
                        </div>
                    </div>
                </div>                           
                <br>
                
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
      </div>
    </div>
  </div>
@endsection