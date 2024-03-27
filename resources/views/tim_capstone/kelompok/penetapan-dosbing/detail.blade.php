@extends('tim_capstone.base.app')

@section('title')
    Penetapan Dosen Pembimbing
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">Penetapan Dosbing</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Penetapan Dosbing</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/penetapan-dosbing') }}" class="btn btn-secondary btn-xs float-right"><i
                            class="bx bx-chevron-left"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <!-- table info -->
                <div class="table-responsive">
                    <form action="{{ url('/admin/penetapan-dosbing/edit-kelompok-process') }}" method="post"
                        autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $kelompok->id }}">
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
                                    <td>Nomor Kelompok</td>
                                    <td>:</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="nomor_kelompok"
                                                    value="{{ old('nomor_kelompok', $kelompok->nomor_kelompok) }}" readonly
                                                    placeholder="Diisi setelah penetapan dosen pembimbing!" required>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status Kelompok</td>
                                    <td>:</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="status_kelompok"
                                                    value="{{ old('status_kelompok', $kelompok->status_kelompok) }}"
                                                    readonly required>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Judul Project</td>
                                    <td>:</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="judul_ta"
                                                    value="{{ old('judul_ta', $kelompok->judul_capstone) }}"
                                                    placeholder="Judul Project" readonly required>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Topik</td>
                                    <td>:</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-select" name="topik" disabled>
                                                    <option value="" selected>-- Pilih --</option>
                                                    @foreach ($rs_topik as $topik)
                                                        <option value="{{ $topik->id }}"
                                                            @if ($topik->nama == $kelompok->nama_topik) selected @endif>
                                                            {{ $topik->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <!-- Hidden input field to send selected value to the server -->
                                                <input type="hidden" name="selected_topik"
                                                    value="{{ $kelompok->nama_topik }}">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="float-end">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
                <hr>

                <h6>List Mahasiswa</h6>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Angkatan</th>
                                <th>Jenis Kelamin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_mahasiswa->count() > 0)
                                @foreach ($rs_mahasiswa as $index => $mahasiswa)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $mahasiswa->user_name }}</td>
                                        <td>{{ $mahasiswa->nomor_induk }}</td>
                                        <td>{{ $mahasiswa->angkatan }}</td>
                                        <td>{{ $mahasiswa->jenis_kelamin }}</td>
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
                <br>
                @if (count($rs_dosbing) >= 2)
                @else
                    <button type="button" class="btn btn-primary btn-xs float-end" data-bs-toggle="modal"
                        data-bs-target="#Dosen">
                        Tambah Dosen Pembimbing
                    </button>
                @endif
                <h6 class="mb-0">List Dosen Pembimbing</h6>
                <br>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Dosbing</th>
                                <th>NIP/NIDN</th>
                                <th>Posisi</th>
                                <th>Status Persetujuan</th>
                                <th>Tindakan</th>
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
                                        <td class="text-center">
                                            <a href="{{ url('/admin/balancing-dosbing/detail') }}/{{ $dosbing->user_id }}"
                                                class="btn btn-outline-secondary btn-xs m-1 "> Detail</a>
                                            <a href="{{ url('/admin/validasi-kelompok/delete-dosen-process') }}/{{ $dosbing->user_id }}/{{ $kelompok->id }}"
                                                class="btn btn-outline-danger btn-xs m-1 "
                                                onclick="return confirm('Apakah anda ingin menghapus {{ $dosbing->user_name }} ?')">
                                                Hapus</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="6">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

    <!-- Modal Dosen pembimbing -->
    <div class="modal fade" id="Dosen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/admin/penetapan-dosbing/add-dosen-kelompok') }}" method="get"
                        autocomplete="off" id="dosbingForm">
                        <input type="hidden" name="id_kelompok" value="{{ $kelompok->id }}">
                        <select class="form-select" name="status_dosen" required id="statusSelect">
                            <option value="" disabled selected>-- Pilih Posisi--</option>
                            <option value="pembimbing 1">Pembimbing 1</option>
                            <option value="pembimbing 2">Pembimbing 2</option>
                        </select>
                        <br>

                        <select class="form-select" name="id_dosen" required id="dosenSelect">
                            <option value="" disabled selected>-- Pilih Dosen--</option>
                            <!-- Options will be populated dynamically -->
                        </select>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Function to update dosbing options based on selected status
                        function updateDosbingOptions() {
                            var statusSelect = document.getElementById('statusSelect');
                            var dosenSelect = document.getElementById('dosenSelect');
                            var status = statusSelect.value;

                            // Clear existing options
                            dosenSelect.innerHTML = '<option value="" disabled selected>-- Pilih Dosen--</option>';

                            // Populate options based on selected status
                            var dosbingArray = status === 'pembimbing 1' ? @json($rs_dosbing1) :
                                @json($rs_dosbing2);
                            dosbingArray.forEach(function(dosbing) {
                                var option = document.createElement('option');
                                option.value = dosbing.user_id;
                                option.textContent = dosbing.user_name;
                                dosenSelect.appendChild(option);
                            });
                        }

                        // Call the function initially
                        updateDosbingOptions();

                        // Add event listener to status select to update dosbing options
                        document.getElementById('statusSelect').addEventListener('change', function() {
                            updateDosbingOptions();
                        });
                    });
                </script>

            </div>
        </div>
    </div>

@endsection
