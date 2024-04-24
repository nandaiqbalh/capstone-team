@extends('tim_capstone.base.app')

@section('title')
    Mahasiswa
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Mahasiswa</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Mahasiswa</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/mahasiswa/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="nama"
                                        value="{{ !empty($nama) ? $nama : '' }}" placeholder="Nama" minlength="1" required>
                                </div>
                                <div class="col-auto mt-1">
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="search">
                                        <i class="bx bx-search-alt-2"></i>
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="row justify-content-end mb-2">
                    <div class="col-auto ">
                        <a href="{{ url('/admin/mahasiswa/add') }}" class="btn btn-info btn-sm float-right"> Tambah Data</a>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Jabatan</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_mahasiswa->count() > 0)
                                @foreach ($rs_mahasiswa as $index => $mahasiswa)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_mahasiswa->firstItem() }}.</td>
                                        <td>{{ $mahasiswa->user_name }}</td>
                                        <td>{{ $mahasiswa->nomor_induk }}</td>
                                        <td>{{ $mahasiswa->role_name }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/admin/mahasiswa/detail') }}/{{ $mahasiswa->user_id }}"
                                                class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                            <a href="{{ url('/admin/mahasiswa/edit') }}/{{ $mahasiswa->user_id }}"
                                                class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                            <button class="btn btn-outline-danger btn-xs m-1"
                                                onclick="confirmDelete('{{ $mahasiswa->user_id }}', '{{ $mahasiswa->user_name }}')">Hapus</button>

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
                        <p>Menampilkan {{ $rs_mahasiswa->count() }} dari total {{ $rs_mahasiswa->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_mahasiswa->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(userId, userName) {
            Swal.fire({
                title: 'Konfirmasi!',
                html: "Apakah anda yakin menghapus <strong>" + userName + "</strong>?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete URL if confirmed
                    window.location.href = "{{ url('/admin/mahasiswa/delete-process') }}/" + userId;
                }
            });
        }
    </script>
@endsection
