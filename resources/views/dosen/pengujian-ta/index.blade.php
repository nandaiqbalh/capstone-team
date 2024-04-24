@extends('tim_capstone.base.app')

@section('title')
    Pengujian Tugas Akhir
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dosen /</span> Pengujian Tugas Akhir</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Pengujian Tugas Akhir</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/dosen/pengujian-ta/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="nama"
                                        value="{{ !empty($nama) ? $nama : '' }}" placeholder="Nomor Kelompok" minlength="3"
                                        required>
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

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Nomor Kelompok</th>
                                <th>Posisi Dosen</th>
                                <th>Status Saya</th>
                                <th>Hari, tanggal</th>
                                <th>Waktu</th>
                                <th>Tempat</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_pengujian_ta->count() > 0)
                                @foreach ($rs_pengujian_ta as $index => $mahasiswa)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_pengujian_ta->firstItem() }}.</td>
                                        <td>{{ $mahasiswa->user_name }}</td>
                                        <td>{{ $mahasiswa->nomor_kelompok }}</td>
                                        <td>{{ $mahasiswa->jenis_dosen }}</td>
                                        @if ($mahasiswa->jenis_dosen == 'Penguji 1')
                                            <td style="color: {{ $mahasiswa->status_penguji1_color }}">
                                                {{ $mahasiswa->status_dosen }}
                                            </td>
                                        @elseif($mahasiswa->jenis_dosen == 'Penguji 2')
                                            <td style="color: {{ $mahasiswa->status_penguji2_color }}">
                                                {{ $mahasiswa->status_dosen }}
                                            </td>
                                        @elseif($mahasiswa->jenis_dosen == 'Pembimbing 2')
                                            <td style="color: {{ $mahasiswa->status_pembimbing2_color }}">
                                                {{ $mahasiswa->status_dosen }}
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ $mahasiswa->hari_sidang }}, {{ $mahasiswa->tanggal_sidang }}</td>
                                        <td>{{ $mahasiswa->waktu_sidang }} WIB - {{ $mahasiswa->waktu_selesai }} WIB
                                        <td>{{ $mahasiswa->nama_ruang }}</td>

                                        <td class="text-center">
                                            @if ($mahasiswa->status_dosen == 'Penguji Setuju!' || $mahasiswa->status_dosen == 'Pembimbing Setuju!')
                                                <a href="{{ url('/dosen/pengujian-ta/tolak') }}/{{ $mahasiswa->id_mahasiswa }}"
                                                    class="btn btn-outline-danger btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $mahasiswa->user_name }}', '{{ url('/dosen/pengujian-ta/tolak') }}/{{ $mahasiswa->id_mahasiswa }}')">
                                                    Tolak</a>
                                            @elseif($mahasiswa->status_dosen == 'Menunggu Persetujuan Penguji!')
                                                <a href="{{ url('/dosen/pengujian-ta/terima') }}/{{ $mahasiswa->id_mahasiswa }}"
                                                    class="btn btn-outline-success btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $mahasiswa->user_name }}', '{{ url('/dosen/pengujian-ta/terima') }}/{{ $mahasiswa->id_mahasiswa }}')">
                                                    Terima</a>
                                                <a href="{{ url('/dosen/pengujian-ta/tolak') }}/{{ $mahasiswa->id_mahasiswa }}"
                                                    class="btn btn-outline-danger btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $mahasiswa->user_name }}', '{{ url('/dosen/pengujian-ta/tolak') }}/{{ $mahasiswa->id_mahasiswa }}')">
                                                    Tolak</a>
                                            @elseif($mahasiswa->status_dosen == 'Penguji Tidak Setuju!' || $mahasiswa->status_dosen == 'Pembimbing Tidak Setuju!')
                                                <a href="{{ url('/dosen/pengujian-ta/terima') }}/{{ $mahasiswa->id_mahasiswa }}"
                                                    class="btn btn-outline-success btn-xs m-1"
                                                    onclick="event.preventDefault(); swalConfirm('{{ $mahasiswa->user_name }}', '{{ url('/dosen/pengujian-ta/terima') }}/{{ $mahasiswa->id_mahasiswa }}')">
                                                    Terima</a>
                                            @else
                                            @endif
                                            <a href="{{ url('/dosen/pengujian-ta/detail') }}/{{ $mahasiswa->id_mahasiswa }}"
                                                class="btn btn-outline-secondary btn-xs m-1"> Detail</a>
                                        </td>

                                        <script>
                                            function swalConfirm(user_name, url) {
                                                Swal.fire({
                                                    title: 'Apakah Anda yakin?',
                                                    text: "Anda akan melakukan tindakan pada " + user_name + " sekelompok?",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Ya, Lanjutkan!',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = url;
                                                    }
                                                });
                                            }
                                        </script>

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
                        <p>Menampilkan {{ $rs_pengujian_ta->count() }} dari total
                            {{ $rs_pengujian_ta->total() }}
                            data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_pengujian_ta->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
