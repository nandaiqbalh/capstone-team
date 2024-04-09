@extends('tim_capstone.base.app')

@section('title')
    Ruang Sidang
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Ruang Sidang</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Ruang Sidang</h5>

            <div class="card-body">

                <br>
                <div class="row justify-content-end mb-2">
                    <div class="col-auto ">
                        <a href="{{ url('/admin/ruangan/add') }}" class="btn btn-info btn-sm float-right"> Tambah Data</a>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Ruang Sidang</th>
                                <th>Kode Ruang Sidang</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_ruangsidang->count() > 0)
                                @foreach ($rs_ruangsidang as $index => $ruangan)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_ruangsidang->firstItem() }}.</td>
                                        <td>{{ $ruangan->nama_ruang }}</td>
                                        <td>{{ $ruangan->kode_ruang }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/admin/ruangan/edit') }}/{{ $ruangan->id }}"
                                                class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                            <button class="btn btn-outline-danger btn-xs m-1"
                                                onclick="confirmDelete('{{ $ruangan->id }}', '{{ $ruangan->nama_ruang }}')">Hapus</button>
                                            <script>
                                                function confirmDelete(topikId, topikNama) {
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
                                                            window.location.href = "{{ url('/admin/ruangan/delete-process') }}/" + topikId;
                                                        }
                                                    });
                                                }
                                            </script>
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
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto mr-auto">
                        <p>Menampilkan {{ $rs_ruangsidang->count() }} dari total {{ $rs_ruangsidang->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_ruangsidang->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
