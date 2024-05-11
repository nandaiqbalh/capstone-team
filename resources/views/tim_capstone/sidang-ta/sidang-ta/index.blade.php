@extends('tim_capstone.base.app')

@section('title')
    Sidang Tugas Akhir
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Sidang Tugas Akhir</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Periode Sidang Tugas Akhir</h5>
            <div class="card-body">
                <div class="row justify-content-end mb-2">
                    <div class="col-auto ">
                        <a href="{{ url('/tim-capstone/sidang-ta/add') }}" class="btn btn-info btn-sm float-right"> Tambah
                            Data</a>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Periode</th>
                                <th>Mulai Pendaftaran</th>
                                <th>Batas Pendaftaran</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_jadwal_periode_sidang_ta->count() > 0)
                                @foreach ($rs_jadwal_periode_sidang_ta as $index => $periode_sidang_ta)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_jadwal_periode_sidang_ta->firstItem() }}.
                                        </td>
                                        <td>{{ $periode_sidang_ta->nama_periode }}</td>
                                        <td>{{ \Carbon\Carbon::parse($periode_sidang_ta->tanggal_mulai)->locale('id_ID')->isoFormat('D MMMM YYYY') }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($periode_sidang_ta->tanggal_selesai)->locale('id_ID')->isoFormat('D MMMM YYYY') }}
                                        </td>
                                        </td>
                                        <td class="text-center">
                                            {{-- <button class="btn btn-outline-secondary btn-xs m-1" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="populateModal('{{ $periode_sidang_ta->nama_periode }}', '{{ $periode_sidang_ta->tanggal_mulai }}', '{{ $periode_sidang_ta->tanggal_selesai }}')">Detail</button> --}}
                                            <a href="{{ url('/tim-capstone/sidang-ta/detail') }}/{{ $periode_sidang_ta->id }}"
                                                class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                            <a href="{{ url('/tim-capstone/sidang-ta/edit') }}/{{ $periode_sidang_ta->id }}"
                                                class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                            <button class="btn btn-outline-danger btn-xs m-1"
                                                onclick="confirmDelete('{{ $periode_sidang_ta->id }}')">Hapus</button>
                                            <script>
                                                function confirmDelete(periode_sidang_taId) {
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
                                                            window.location.href = "{{ url('/tim-capstone/sidang-ta/delete-process') }}/" +
                                                                periode_sidang_taId;
                                                        }
                                                    });
                                                }
                                            </script>
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
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto mr-auto">
                        <p>Menampilkan {{ $rs_jadwal_periode_sidang_ta->count() }} dari total
                            {{ $rs_jadwal_periode_sidang_ta->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_jadwal_periode_sidang_ta->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
