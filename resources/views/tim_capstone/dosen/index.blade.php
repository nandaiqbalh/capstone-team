@extends('tim_capstone.base.app')

@section('title')
    Dosen
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Dosen</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Dosen</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/dosen/search') }}" method="get" autocomplete="off">
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
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="reset">
                                        <i class="bx bx-reset"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="row justify-content-end mb-2">
                    <div class="col-auto ">
                        <a href="{{ url('/admin/dosen/add') }}" class="btn btn-primary btn-xs float-right"><i
                                class="fas fa-plus"></i> Tambah Data</a>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th width="5%">Pembimbing 1</th>
                                <th width="5%">Pembimbing 2</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dt_dosen->count() > 0)
                                @foreach ($dt_dosen as $index => $dosen)
                                    <tr>
                                        <td class="text-center">{{ $index + $dt_dosen->firstItem() }}.</td>
                                        <td>{{ $dosen->user_name }}</td>
                                        <td>
                                            @if ($dosen->dosbing1 == 1)
                                                <a href="{{ route('to.inaktif.ketersediaan.1', ['id' => $dosen->user_id]) }}"
                                                    class="btn btn-outline-secondary btn-xs m-1 ">Tersedia</a>
                                            @else
                                                <a href="{{ route('to.aktif.ketersediaan.1', ['id' => $dosen->user_id]) }}"
                                                    class="btn btn-outline-danger btn-xs m-1">Tidak Tersedia</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($dosen->dosbing2 == 1)
                                                <a href="{{ route('to.inaktif.ketersediaan.2', ['id' => $dosen->user_id]) }}"
                                                    class="btn btn-outline-primary btn-xs m-1 ">Tersedia</a>
                                            @else
                                                <a href="{{ route('to.aktif.ketersediaan.2', ['id' => $dosen->user_id]) }}"
                                                    class="btn btn-outline-danger btn-xs m-1">Tidak Tersedia</a>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ url('/admin/dosen/detail') }}/{{ $dosen->user_id }}"
                                                class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                            <a href="{{ url('/admin/dosen/edit') }}/{{ $dosen->user_id }}"
                                                class="btn btn-outline-warning btn-xs m-1 "> Ubah</a>
                                            <button class="btn btn-outline-danger btn-xs m-1"
                                                onclick="confirmDelete('{{ $dosen->user_id }}', '{{ $dosen->user_name }}')">Hapus</button>
                                            <script>
                                                function confirmDelete(userId, userName) {
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
                                                            window.location.href = "{{ url('/admin/dosen/delete-process') }}/" + userId;
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
                        <p>Menampilkan {{ $dt_dosen->count() }} dari total {{ $dt_dosen->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $dt_dosen->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleCheckboxes = document.querySelectorAll('.toggle-status');

            toggleCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const id = this.getAttribute('data-id');
                    const status = this.checked ? 1 : 0;

                    // Kirim permintaan menggunakan fetch API
                    fetch(`/toggle-status/${id}/${status}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data);
                            // Tindakan setelah permintaan berhasil
                        })
                        .catch(error => {
                            console.error('There was an error!', error);
                        });
                });
            });
        });
    </script>

@endsection
