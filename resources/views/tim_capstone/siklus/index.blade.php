@extends('tim_capstone.base.app')

@section('title')
    Siklus
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Siklus</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Siklus</h5>
            <div class="card-body">
                <br>
                <div class="row justify-content-end mb-2">
                    <div class="col-auto ">
                        <a href="{{ url('/admin/siklus/add') }}" class="btn btn-info btn-sm float-right"> Tambah Data</a>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama - Tahun Ajaran</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Pendaftaran Mulai</th>
                                <th>Pendaftaran Selesai</th>
                                <th>Status</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dt_siklus->count() > 0)
                                @foreach ($dt_siklus as $index => $siklus)
                                    <tr>
                                        <td class="text-center">{{ $index + $dt_siklus->firstItem() }}.</td>
                                        <td>{{ $siklus->tahun_ajaran }}</td>
                                        <td>{{ \Carbon\Carbon::parse($siklus->tanggal_mulai)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($siklus->tanggal_selesai)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($siklus->pendaftaran_mulai)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($siklus->pendaftaran_selesai)->format('d-m-Y') }}</td>
                                        <td class= "text-center">
                                            @if ($siklus->status == 'aktif')
                                                <span class="text-success">Aktif</span>
                                            @elseif($siklus->status == 'tidak aktif')
                                                <span class="text-danger">Tidak aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-secondary btn-xs m-1" data-bs-toggle="modal"
                                                data-bs-target="#detailModal"
                                                onclick="populateModal('{{ $siklus->tahun_ajaran }}', '{{ $siklus->tanggal_mulai }}', '{{ $siklus->tanggal_selesai }}', '{{ $siklus->pendaftaran_mulai }}', '{{ $siklus->pendaftaran_selesai }}', '{{ $siklus->status }}')">Detail</button>
                                            <a href="{{ url('/admin/siklus/edit') }}/{{ $siklus->id }}"
                                                class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                            <button class="btn btn-outline-danger btn-xs m-1"
                                                onclick="confirmDelete('{{ $siklus->id }}')">Hapus</button>
                                            <script>
                                                function confirmDelete(siklusId) {
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
                                                            window.location.href = "{{ url('/admin/siklus/delete-process') }}/" + siklusId;
                                                        }
                                                    });
                                                }
                                            </script>
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
                        <p>Menampilkan {{ $dt_siklus->count() }} dari total {{ $dt_siklus->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $dt_siklus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Siklus-->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="detailModalLabel"><strong>Detail Siklus</strong></h5>
                </div>
                <div class="modal-body">
                    <p><strong>Nama - Tahun Ajaran:</strong> <span id="modal-tahun-ajaran"></span></p>
                    <p><strong>Tanggal Mulai:</strong> <span id="modal-tanggal-mulai"></span></p>
                    <p><strong>Tanggal Selesai:</strong> <span id="modal-tanggal-selesai"></span></p>
                    <p><strong>Pendaftaran Mulai:</strong> <span id="modal-pendaftaran-mulai"></span></p>
                    <p><strong>Pendaftaran Selesai:</strong> <span id="modal-pendaftaran-selesai"></span></p>
                    <p><strong>Status:</strong> <span id="modal-status"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk populate modal -->
    <script>
        function populateModal(tahunAjaran, tanggalMulai, tanggalSelesai, pendaftaranMulai, pendaftaranSelesai, status) {
            document.getElementById("modal-tahun-ajaran").innerText = tahunAjaran;
            document.getElementById("modal-tanggal-mulai").innerText = tanggalMulai.substr(0, 10);
            document.getElementById("modal-tanggal-selesai").innerText = tanggalSelesai.substr(0, 10);
            document.getElementById("modal-pendaftaran-mulai").innerText = pendaftaranMulai.substr(0, 10);
            document.getElementById("modal-pendaftaran-selesai").innerText = pendaftaranSelesai.substr(0, 10);
            document.getElementById("modal-status").innerText = status;
        }
    </script>

@endsection
