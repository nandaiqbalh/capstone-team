@extends('tim_capstone.base.app')

@section('title')
    Expo Project
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Expo Project</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Expo Project</h5>

            <div class="card-body">

                <div class="row justify-content-end mb-2">
                    <div class="col-auto ">
                        <a href="{{ url('/admin/expo-project/add') }}" class="btn btn-info btn-sm float-right"> Tambah
                            Data</a>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Periode</th>
                                <th>Siklus</th>
                                <th>Tempat</th>
                                <th>Hari, Tanggal</th>
                                <th>Waktu</th>
                                <th>Batas Pendaftaran</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_expo->count() > 0)
                                @foreach ($rs_expo as $index => $pendaftaran)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_expo->firstItem() }}.</td>
                                        <td>{{ $pendaftaran->nama_periode }}</td>
                                        <td>{{ $pendaftaran->nama_siklus }}</td>
                                        <td>{{ $pendaftaran->tempat }}</td>
                                        <td>{{ $pendaftaran->hari_expo }}, {{ $pendaftaran->tanggal_expo }}</td>
                                        <td>{{ $pendaftaran->waktu_expo }} WIB</td>
                                        <td>{{ $pendaftaran->tanggal_selesai }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/admin/expo-project/detail') }}/{{ $pendaftaran->id }}"
                                                class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                            <a href="{{ url('/admin/expo-project/edit') }}/{{ $pendaftaran->id }}"
                                                class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                            <button class="btn btn-outline-danger btn-xs m-1"
                                                onclick="confirmDelete('{{ $pendaftaran->id }}', '{{ $pendaftaran->nama_siklus }}')">Hapus</button>
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
                                                            window.location.href = "{{ url('/admin/expo-project/delete-process') }}/" +
                                                                pendaftaranId;
                                                        }
                                                    });
                                                }
                                            </script>
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
                        <p>Menampilkan {{ $rs_expo->count() }} dari total {{ $rs_expo->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_expo->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
