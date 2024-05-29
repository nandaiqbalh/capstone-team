@extends('tim_capstone.base.app')

@section('title')
    Kelompok Valid
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Kelompok Valid</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Kelompok</h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/tim-capstone/kelompok-valid/search') }}" method="get"
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

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <form action="{{ url('/tim-capstone/kelompok-valid/filter-kelompok-progress-dan-siklus') }}"
                        method="get" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-8"> <!-- Menyesuaikan dengan lebar yang diinginkan -->
                                <div class="mb-3">
                                    <select class="form-select select-2" name="id_siklus" required>
                                        <option value="" disabled selected> -- Filter Berdasarkan Siklus -- </option>
                                        @foreach ($rs_siklus as $s)
                                            <option value="{{ $s->id }}"
                                                {{ isset($siklus) && $siklus->id == $s->id ? 'selected' : '' }}>
                                                {{ $s->nama_siklus }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <select class="form-select select-2" name="id_progress" required>
                                        <option value="" disabled selected> -- Filter Berdasarkan Progress --
                                        </option>
                                        <option value="1" {{ $progress == 1 ? 'selected' : '' }}>Kelompok Valid
                                        </option>

                                        <option value="2" {{ $progress == 2 ? 'selected' : '' }}>Kelompok Belum
                                            Disetujui
                                            C100</option>
                                        <option value="3" {{ $progress == 3 ? 'selected' : '' }}>Kelompok Disetujui
                                            C100</option>
                                        <option value="4" {{ $progress == 4 ? 'selected' : '' }}>Kelompok Belum Sidang
                                            Proposal</option>
                                        <option value="5" {{ $progress == 5 ? 'selected' : '' }}>Kelompok Sudah Sidang
                                            Proposal</option>
                                        <option value="6" {{ $progress == 6 ? 'selected' : '' }}>Kelompok Belum
                                            Disetujui
                                            C200</option>
                                        <option value="7" {{ $progress == 7 ? 'selected' : '' }}>Kelompok Disetujui
                                            C200</option>
                                        <option value="8" {{ $progress == 8 ? 'selected' : '' }}>Kelompok Belum
                                            Disetujui
                                            C300</option>
                                        <option value="9" {{ $progress == 9 ? 'selected' : '' }}>Kelompok Disetujui
                                            C300</option>
                                        <option value="10" {{ $progress == 10 ? 'selected' : '' }}>Kelompok Belum
                                            Disetujui
                                            C400</option>
                                        <option value="11" {{ $progress == 11 ? 'selected' : '' }}>Kelompok Disetujui
                                            C400</option>
                                        <option value="12" {{ $progress == 12 ? 'selected' : '' }}>Kelompok Belum
                                            Disetujui
                                            C500</option>
                                        <option value="13" {{ $progress == 13 ? 'selected' : '' }}>Kelompok Disetujui
                                            C500</option>
                                        <option value="14" {{ $progress == 14 ? 'selected' : '' }}>Kelompok Belum Lulus
                                            Expo
                                        </option>
                                        <option value="15" {{ $progress == 15 ? 'selected' : '' }}>Kelompok Lulus Expo
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4"> <!-- Menyesuaikan dengan lebar yang diinginkan -->
                                <button type="submit" class="btn btn-primary float-end" name="action"
                                    value="filter">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>

                </div>
                <br>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nomor Kelompok</th>
                                @if ($progress == 2 || $progress == 3)
                                    <th>Status C100</th>
                                @elseif ($progress == 4 || $progress == 5)
                                    <th>Status Sempro</th>
                                @elseif ($progress == 6 || $progress == 7)
                                    <th>Status C200</th>
                                @elseif ($progress == 8 || $progress == 9)
                                    <th>Status C300</th>
                                @elseif ($progress == 10 || $progress == 11)
                                    <th>Status C400</th>
                                @elseif ($progress == 12 || $progress == 13)
                                    <th>Status C500</th>
                                @elseif ($progress == null)
                                    <!-- Add any additional conditions if needed -->
                                @else
                                    <!-- Add any additional conditions if needed -->
                                @endif
                                <th>Status Kelompok</th>

                                <th>Siklus Pendaftaran</th>
                                <th>Lulus</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_kelompok->count() > 0)
                                @foreach ($rs_kelompok as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_kelompok->firstItem() }}.</td>
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                        @if ($progress == 2 || $progress == 3)
                                            <td style="color: {{ $kelompok->status_dokumen_color }}">
                                                {{ $kelompok->file_status_c100 }}</td>
                                        @elseif ($progress == 4 || $progress == 5)
                                            <td>
                                                @if ($kelompok->is_sidang_proposal == 1)
                                                    <span style="color: #44B158">Sudah Sidang</span>
                                                @else
                                                    <span style="color: #FF0000">Belum Sidang</span>
                                                @endif
                                            </td>
                                        @elseif ($progress == 6 || $progress == 7)
                                            <td style="color: {{ $kelompok->status_dokumen_color }}">
                                                {{ $kelompok->file_status_c200 }}</td>
                                        @elseif ($progress == 8 || $progress == 9)
                                            <td style="color: {{ $kelompok->status_dokumen_color }}">
                                                {{ $kelompok->file_status_c300 }}</td>
                                        @elseif ($progress == 10 || $progress == 11)
                                            <td style="color: {{ $kelompok->status_dokumen_color }}">
                                                {{ $kelompok->file_status_c400 }}</td>
                                        @elseif ($progress == 12 || $progress == 13)
                                            <td style="color: {{ $kelompok->status_dokumen_color }}">
                                                {{ $kelompok->file_status_c500 }}</td>
                                        @elseif ($progress == null)
                                            <!-- Add any additional conditions if needed -->
                                        @else
                                            <!-- Add any additional conditions if needed -->
                                        @endif
                                        <td style="color: {{ $kelompok->status_kelompok_color }}">
                                            {{ $kelompok->status_kelompok }}</td>

                                        <td>{{ $kelompok->nama_siklus }}</td>
                                        <td>
                                            @if ($kelompok->is_lulus_expo == 1)
                                                <span style="color: #44B158">Lulus Expo!</span>
                                            @else
                                                <span style="color: #FF0000">Belum Lulus Expo!</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('/tim-capstone/kelompok-valid/detail') }}/{{ $kelompok->id }}"
                                                class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                            <button class="btn btn-outline-danger btn-xs m-1"
                                                onclick="confirmDelete('{{ $kelompok->id }}', '{{ $kelompok->nomor_kelompok }}')">Hapus</button>
                                            <script>
                                                function confirmDelete(kelompokId, nomorKelompok) {
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
                                                            window.location.href = "{{ url('/tim-capstone/kelompok-valid/delete-process') }}/" +
                                                                kelompokId;
                                                        }
                                                    });
                                                }
                                            </script>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    @if ($progress == 0)
                                        <td class="text-center" colspan="6">Tidak ada data.</td>
                                    @else
                                        <td class="text-center" colspan="7">Tidak ada data.</td>
                                    @endif
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto mr-auto">
                        <p>Menampilkan {{ $rs_kelompok->count() }} dari total {{ $rs_kelompok->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_kelompok->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
