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
                <div class="row justify-content-end mb-2">
                    <div class="col-auto ">
                        <a href="{{ url('/tim-capstone/siklus/add') }}" class="btn btn-info btn-sm float-right"> Tambah
                            Data</a>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Siklus</th>
                                <th>Kode</th>
                                <th>Pendaftaran Mulai</th>
                                <th>Pendaftaran Selesai</th>
                                <th>Batas Submit C100</th>
                                <th>Status</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dt_siklus->count() > 0)
                                @foreach ($dt_siklus as $index => $siklus)
                                    <tr>
                                        <td class="text-center">{{ $index + $dt_siklus->firstItem() }}.</td>
                                        <td>{{ $siklus->nama_siklus }}</td>
                                        <td>{{ $siklus->kode_siklus }}</td>
                                        <td>{{ \Carbon\Carbon::parse($siklus->pendaftaran_mulai)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($siklus->pendaftaran_selesai)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($siklus->batas_submit_c100)->format('d-m-Y') }}</td>

                                        <td class= "text-center">
                                            @if ($siklus->status == 'aktif')
                                                <span class="text-success">Aktif</span>
                                            @elseif($siklus->status == 'tidak aktif')
                                                <span class="text-danger">Tidak aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="{{ url('/tim-capstone/siklus/detail') }}/{{ $siklus->id }}"
                                                class="btn btn-outline-secondary btn-xs m-1 "> Detail</a> --}}
                                            <a href="{{ url('/tim-capstone/siklus/edit') }}/{{ $siklus->id }}"
                                                class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                            <button class="btn btn-outline-danger btn-xs m-1"
                                                onclick="confirmDelete('{{ $siklus->id }}', '{{ $siklus->nama_siklus }}')">Hapus</button>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="7">Tidak ada data.</td>
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
    <script>
        function confirmDelete(siklusId, siklusName) {
            Swal.fire({
                title: 'Konfirmasi!',
                html: "Apakah anda yakin menghapus <strong>" + siklusName + "</strong>?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete URL if confirmed
                    window.location.href = "{{ url('/tim-capstone/siklus/delete-process') }}/" + siklusId;
                }
            });
        }
    </script>
@endsection
