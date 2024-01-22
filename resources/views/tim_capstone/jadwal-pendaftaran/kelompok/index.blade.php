@extends('tim_capstone.base.app')

@section('title')
Jadwal Pendaftaran Kelompok
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Jadwal Pendaftaran Kelompok</h5>
    <!-- notification -->
    @include("template.notification")

    <!-- Bordered Table -->
    <div class="card">
        <h5 class="card-header">Data Jadwal Pendaftaran Kelompok</h5>

        <div class="card-body">


            <div class="row justify-content-end mb-2">
                <div class="col-auto ">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah Data
                    </button>
                </div>
            </div>

            <br>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Siklus</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rs_pendaftaran->count() > 0)
                        @foreach($rs_pendaftaran as $index => $pendaftaran)
                        <tr>
                            <td class="text-center">{{ $index + $rs_pendaftaran->firstItem() }}.</td>
                            <td>{{ $pendaftaran->tahun_ajaran }}</td>
                            <td>{{ $pendaftaran->tanggal_mulai }}</td>
                            <td>{{ $pendaftaran->tanggal_selesai }}</td>
                            <td class="text-center">
                                {{-- <a href="{{ url('/admin/jadwal-pendaftaran/kelompok/detail') }}/{{ $pendaftaran->id }}" class="btn btn-outline-secondary btn-xs m-1 "> Detail</a> --}}
                                <button type="button" class="btn btn-outline-warning btn-xs m-1" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $pendaftaran->id }}"> Ubah</button>
                                <!-- <a href="{{ url('/admin/jadwal-pendaftaran/kelompok/delete-process') }}/{{ $pendaftaran->id }}" class="btn btn-outline-danger btn-xs m-1 " onclick="return confirm('Apakah anda ingin menghapus {{ $pendaftaran->tahun_ajaran }} ?')"> Hapus</a> -->
                                <button class="btn btn-outline-danger btn-xs m-1" onclick="confirmDelete('{{ $pendaftaran->id }}', '{{ $pendaftaran->tahun_ajaran }}')">Hapus</button>
                                    <script>
                                        function confirmDelete(pendaftaranId, tahunAjaran) {
                                            Swal.fire({
                                                title: 'Apakah Anda yakin?',
                                                text: "Anda tidak akan dapat mengembalikan ini!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Ya, hapus!',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    // Redirect to the delete URL if confirmed
                                                    window.location.href = "{{ url('/admin/jadwal-pendaftaran/kelompok/delete-process') }}/" + pendaftaranId;
                                                }
                                            });
                                        }
                                    </script>
                            </td>
                        </tr>
                        {{-- modal edit --}}
                        <div class="modal fade" id="exampleModal{{ $pendaftaran->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Ubah Jadwal Pendaftaran Kelompok</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('/admin/jadwal-pendaftaran/kelompok/edit-process') }}" method="post" autocomplete="off">
                                        {{ csrf_field()}}
                                        <input type="hidden" name="id" value="{{ $pendaftaran->id }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Pilih Siklus <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="siklus_id" required>
                                                        <option value="" disabled selected>-- Pilih --</option>
                                                        @foreach ($rs_siklus as $siklus)
                                                        <option value="{{$siklus->id}}" @if ($siklus->id == $pendaftaran->siklus_id ) selected @endif >{{$siklus->tahun_ajaran}} | {{$siklus->tanggal_mulai}} sampai {{$siklus->tanggal_selesai}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label>Tanggal Mulai<span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="tanggal_mulai" value="{{ old('tanggal_mulai',$pendaftaran->tanggal_mulai) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label >Tanggal Selesai<span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="tanggal_selesai" value="{{ old('tanggal_selesai',$pendaftaran->tanggal_selesai) }}" required>
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
                    <p>Menampilkan {{ $rs_pendaftaran->count() }} dari total {{ $rs_pendaftaran->total() }} data.</p>
                </div>
                <div class="col-auto ">
                    {{ $rs_pendaftaran->links() }}
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
          <h5 class="modal-title" id="exampleModalLabel">Tambah Jadwal Pendaftaran Kelompok</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ url('/admin/jadwal-pendaftaran/kelompok/add-process') }}" method="post" autocomplete="off">
                {{ csrf_field()}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Pilih Siklus <span class="text-danger">*</span></label>
                            <select class="form-select" name="siklus_id" required>
                                <option value="" disabled selected>-- Pilih --</option>
                                @foreach ($rs_siklus as $siklus)
                                <option value="{{$siklus->id}}">{{$siklus->tahun_ajaran}} | {{$siklus->tanggal_mulai}} sampai {{$siklus->tanggal_selesai}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Tanggal Mulai<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label >Tanggal Selesai<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
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

@endsection
