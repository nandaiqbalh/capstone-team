@extends('tim_capstone.base.app')

@section('title')
    Sidang Proposal
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Mahasiswa /</span> Sidang Proposal</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Sidang Proposal</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <br>
                @if ($kelompok != null)
                    @if ($kelompok->nomor_kelompok == null)

                        <h6>Kelompok Anda belum valid!</h6>
                    @elseif ($siklus_sudah_punya_kelompok == null)
                        <h6>Siklus capstone sudah tidak aktif!</h6>
                    @elseif($rs_sidang == null)
                        <h6>Belum ada jadwal sidang!</h6>
                    @else
                        @if ($akun_mahasiswa->status_individu == 'Didaftarkan!')

                            <div>
                                <script>
                                    function confirmDelete(userName) {
                                        Swal.fire({
                                            title: 'Konfirmasi',
                                            text: "Setuju bergabung dengan " + userName + "?",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Ya, setuju',
                                            cancelButtonText: 'Batalkan'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Send POST request to accept URL if confirmed
                                                sendPostRequest("{{ route('kelompok.accept') }}");
                                            } else {
                                                // Send POST request to cancel URL if canceled
                                                sendPostRequest("{{ route('kelompok.reject') }}");
                                            }
                                        });
                                    }

                                    function sendPostRequest(url) {
                                        // Create form element
                                        var form = document.createElement("form");
                                        form.setAttribute("method", "POST");
                                        form.setAttribute("action", url);

                                        // Create CSRF token input field
                                        var csrfToken = document.createElement("input");
                                        csrfToken.setAttribute("type", "hidden");
                                        csrfToken.setAttribute("name", "_token");
                                        csrfToken.setAttribute("value", "{{ csrf_token() }}");

                                        // Append CSRF token input to form
                                        form.appendChild(csrfToken);

                                        // Append form to body and submit
                                        document.body.appendChild(form);
                                        form.submit();
                                    }

                                    confirmDelete("{{ $kelompok->pengusul_kelompok }}");
                                </script>
                            </div>
                        @else
                            <!-- table info -->
                            <div class="table-responsive">
                                <table class="table table-borderless table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="20%"></th>
                                            <th width="5%"></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Status</td>
                                            <td>:</td>
                                            @if ($kelompok->nomor_kelompok == null)
                                                <td>Menunggu Validasi Kelompok!</td>
                                            @else
                                                <td>{{ $kelompok->status_kelompok }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Hari, tanggal</td>
                                            <td>:</td>
                                            @if ($kelompok->nomor_kelompok == null)
                                                <td>!</td>
                                            @else
                                                <td>{{ $rs_sidang->hari_sidang }}, {{ $rs_sidang->tanggal_sidang }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Waktu</td>
                                            <td>:</td>
                                            @if ($kelompok->nomor_kelompok == null)
                                                <td>-</td>
                                            @else
                                                <td>{{ $rs_sidang->waktu_sidang }} WIB</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Ruang Sidang</td>
                                            <td>:</td>
                                            @if ($kelompok->nomor_kelompok == null)
                                                <td>-</td>
                                            @else
                                                <td>{{ $rs_sidang->nama_ruang }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Judul Capstone</td>
                                            <td>:</td>
                                            @if ($kelompok->nomor_kelompok == null)
                                                <td>-</td>
                                            @else
                                                <td>{{ $kelompok->judul_capstone }}</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- list mahasiswa  --}}

                            <br>
                            <h5 class="mb-0">List Mahasiswa</h5>

                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr class="text-center">
                                            <th width="5%">No</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>NIM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($rs_mahasiswa->count() > 0)
                                            @foreach ($rs_mahasiswa as $index => $mahasiswa)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}.</td>
                                                    <td>{{ $mahasiswa->user_name }}</td>
                                                    <td>{{ $mahasiswa->nomor_induk }}</td>
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

                            {{-- list dos pembimbing  --}}

                            <br>
                            <h5 class="mb-0">List Dosen Pembimbing</h5>

                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr class="text-center">
                                            <th width="5%">No</th>
                                            <th>Nama Dosbing</th>
                                            <th>NIP/NIDN</th>
                                            <th>Posisi</th>
                                            <th>Status Persetujuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($rs_dosbing->count() > 0)
                                            @foreach ($rs_dosbing as $index => $dosbing)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}.</td>
                                                    <td>{{ $dosbing->user_name }}</td>
                                                    <td>{{ $dosbing->nomor_induk }}</td>
                                                    <td>{{ $dosbing->jenis_dosen }}</td>
                                                    <td>{{ $dosbing->status_dosen }}</td>
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

                            {{-- list dos penguji  --}}

                            <br>
                            <h5 class="mb-0">List Dosen Penguji</h5>

                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr class="text-center">
                                            <th width="5%">No</th>
                                            <th>Nama Dosen Penguji</th>
                                            <th>NIP/NIDN</th>
                                            <th>Posisi</th>
                                            <th>Status Persetujuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($rs_dospeng->count() > 0)
                                            @foreach ($rs_dospeng as $index => $dospeng)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}.</td>
                                                    <td>{{ $dospeng->user_name }}</td>
                                                    <td>{{ $dospeng->nomor_induk }}</td>
                                                    <td>{{ $dospeng->jenis_dosen }}</td>
                                                    <td>{{ $dospeng->status_dosen }}</td>
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
                        @endif

                    @endif
                @else
                    <h6>Anda belum mendaftar capstone!</h6>
                @endif
            </div>
        </div>
    </div>
@endsection
