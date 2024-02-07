@extends('tim_capstone.base.app')

@section('title')
Bimbingan Saya
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Bimbingan Saya</h5>
    <!-- notification -->
    @include("template.notification")

    <!-- Bordered Table -->
    <div class="card">
        <h5 class="card-header">Data Bimbingan Saya</h5>

        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-12">
                    <form class="form-inline" action="{{ url('/admin/kelompok/search') }}" method="get" autocomplete="off">
                        <div class="row">
                            <div class="col-auto mt-1">
                                <input class="form-control mr-sm-2" type="search" name="nama" value="{{ !empty($nama) ? $nama : '' }}" placeholder="Nama Role" minlength="3" required>
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


            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nomor Kelompok</th>
                            <th>Judul Capstone</th>
                            <th>Topik</th>
                            <th>Dosen</th>
                            <th>Status</th>
                            <th width="18%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rs_bimbingan_saya->count() > 0)
                        @foreach($rs_bimbingan_saya as $index => $kelompok)
                        <tr>
                            <td class="text-center">{{ $index + $rs_bimbingan_saya->firstItem() }}.</td>
                            <td>{{ $kelompok->nomor_kelompok }}</td>
                            <td>{{ $kelompok->judul_capstone }}</td>
                            <td>{{ $kelompok->nama_topik }}</td>
                            <td>{{ $kelompok->status_dosen }}</td>
                            <td>{{ $kelompok->status_persetujuan }}</td>

                            <td class="text-center">
                                @if($kelompok->status_persetujuan == 'disetujui')
                                    <a href="{{ route('dosen.bimbingan-saya.tolak', ['id_dosen_kelompok' => $kelompok->id]) }}" class="btn btn-outline-danger btn-xs m-1" onclick="showRejectConfirmation('{{ $kelompok->nomor_kelompok }}'); return false;">Tolak</a>
                                @elseif($kelompok->status_persetujuan == 'tidak disetujui')
                                    <a href="{{ route('dosen.bimbingan-saya.terima', ['id_dosen_kelompok' => $kelompok->id]) }}" class="btn btn-outline-primary btn-xs m-1" onclick="showAcceptConfirmation('{{ $kelompok->nomor_kelompok }}'); return false;">Terima</a>
                                @else
                                    <a href="{{ route('dosen.bimbingan-saya.terima', ['id_dosen_kelompok' => $kelompok->id]) }}" class="btn btn-outline-primary btn-xs m-1" onclick="showAcceptConfirmation('{{ $kelompok->nomor_kelompok }}'); return false;">Terima</a>
                                    <a href="{{ route('dosen.bimbingan-saya.tolak', ['id_dosen_kelompok' => $kelompok->id]) }}" class="btn btn-outline-danger btn-xs m-1 " onclick="showRejectConfirmation('{{ $kelompok->nomor_kelompok }}'); return false;">Tolak</a>
                                @endif
                                <a href="{{ url('/dosen/bimbingan-saya/detail') }}/{{ $kelompok->id }}" class="btn btn-outline-warning btn-xs m-1 "> Detail</a>
                            </td>
                            <!-- percobaan 2 pake modal -->
                            <!-- Modal Tolak -->
                            <div class="modal fade" id="tolakModal" tabindex="-1" role="dialog" aria-labelledby="tolakModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="tolakModalLabel">Konfirmasi Tolak</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah anda ingin menolak kelompok {{ $kelompok->nomor_kelompok }} ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <a href="{{ url('/dosen/bimbingan-saya/tolak') }}/{{ $kelompok->id_dosen_kelompok }}" class="btn btn-danger">Tolak</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Terima -->
                            <div class="modal fade" id="terimaModal" tabindex="-1" role="dialog" aria-labelledby="terimaModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="terimaModalLabel">Konfirmasi Terima</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah anda ingin menerima kelompok {{ $kelompok->nomor_kelompok }} ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <a href="{{ url('/dosen/bimbingan-saya/terima') }}/{{ $kelompok->id_dosen_kelompok }}" class="btn btn-primary">Terima</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- percobaan 1 sweetalert -->
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                            <script>
                            function showAcceptConfirmation(nomorKelompok) {
                                Swal.fire({
                                    title: 'Konfirmasi Terima',
                                    text: "Apakah anda ingin menerima kelompok " + nomorKelompok + " ?",
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Terima',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redirect to the acceptance URL
                                        window.location.href = "{{ url('/dosen/bimbingan-saya/terima') }}/{{ $kelompok->id_dosen_kelompok }}";
                                    }
                                });
                            }
                            function showRejectConfirmation(nomorKelompok) {
                                Swal.fire({
                                    title: 'Konfirmasi Tolak',
                                    text: "Apakah anda ingin menolak kelompok " + nomorKelompok + " ?",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Tolak',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redirect to the rejection URL
                                        window.location.href = "{{ url('/dosen/bimbingan-saya/tolak') }}/{{ $kelompok->id_dosen_kelompok }}";
                                    }
                                });
                            }
                            </script>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="6">Tidak ada data.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- pagination -->
            <div class="row mt-3 justify-content-between">
                <div class="col-auto mr-auto">
                    <p>Menampilkan {{ $rs_bimbingan_saya->count() }} dari total {{ $rs_bimbingan_saya->total() }} data.</p>
                </div>
                <div class="col-auto ">
                    {{ $rs_bimbingan_saya->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
