@extends('tim_capstone.base.app')

@section('title')
    Penjadwalan Sidang Proposal
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">Penjadwalan Sidang Proposal</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Data Kelompok</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/penjadwalan-sidang-proposal') }}" class="btn btn-secondary btn-xs float-right"><i
                            class="bx bx-chevron-left"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
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
                                <td>Nomor Kelompok</td>
                                <td>:</td>

                                @if ($kelompok->nomor_kelompok == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $kelompok->nomor_kelompok }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Status Kelompok</td>
                                <td>:</td>

                                @if ($kelompok->status_kelompok == null)
                                    <td>-</td>
                                @else
                                    <td style="color: {{ $kelompok->status_kelompok_color }}">
                                        {{ $kelompok->status_kelompok }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Status Sidang Proposal</td>
                                <td>:</td>

                                @if ($kelompok->status_sidang_proposal == null)
                                    <td style="color: {{ $kelompok->status_sidang_color }}"">Belum Dijadwalkan Sidang
                                        Proposal!</td>
                                @else
                                    <td
                                        style="color:
                                        {{ $kelompok->status_sidang_color }}">
                                        {{ $kelompok->status_sidang_proposal }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Status Dokumen C100</td>
                                <td>:</td>

                                @if ($kelompok->file_status_c100 == null)
                                    <td>-</td>
                                @else
                                    <td style="color: {{ $kelompok->status_dokumen_color }}">
                                        {{ $kelompok->file_status_c100 }}</td>
                                @endif
                            </tr>

                            <tr>
                                <td>Hari, tanggal</td>
                                <td>:</td>

                                @if ($jadwal_sidang == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $jadwal_sidang->hari_sidang }}, {{ $jadwal_sidang->tanggal_sidang }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td>:</td>

                                @if ($jadwal_sidang == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $jadwal_sidang->waktu_sidang }} WIB - {{ $jadwal_sidang->waktu_selesai }} WIB
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>Tempat</td>
                                <td>:</td>

                                @if ($jadwal_sidang == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $jadwal_sidang->nama_ruang }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Judul Capstone</td>
                                <td>:</td>

                                @if ($kelompok->judul_capstone == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $kelompok->judul_capstone }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Topik</td>
                                <td>:</td>

                                @if ($kelompok->nama_topik == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $kelompok->nama_topik }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
                <hr>

                <br>
                @if (count($rs_penguji_proposal) >= 2)
                @else
                    <button type="button" class="btn btn-primary btn-xs float-end" data-bs-toggle="modal"
                        data-bs-target="#Dosen">
                        Tambah Dosen Penguji Proposal
                    </button>
                @endif

                <h6 class="mb-0">List Dosen Penguji Proposal</h6>
                <br>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nama Penguji</th>
                                <th>NIP/NIDN</th>
                                <th>Posisi</th>
                                <th>Status Persetujuan</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_penguji_proposal->count() > 0)
                                @foreach ($rs_penguji_proposal as $index => $penguji_proposal)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $penguji_proposal->user_name }}</td>
                                        <td>{{ $penguji_proposal->nomor_induk }}</td>
                                        <td>{{ $penguji_proposal->jenis_dosen }}</td>
                                        @if ($penguji_proposal->jenis_dosen == 'Penguji 1')
                                            <td style="color: {{ $kelompok->status_penguji1_color }}">
                                                {{ $penguji_proposal->status_dosen }}</td>
                                        @elseif($penguji_proposal->jenis_dosen == 'Penguji 2')
                                            <td style="color: {{ $kelompok->status_penguji2_color }}">
                                                {{ $penguji_proposal->status_dosen }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td class="text-center">
                                            <a href="{{ url('/admin/penjadwalan-sidang-proposal/delete-dosen-penguji') }}/{{ $penguji_proposal->user_id }}/{{ $kelompok->id }}"
                                                class="btn btn-outline-danger btn-xs m-1 "
                                                onclick="return confirm('Apakah anda ingin menghapus {{ $penguji_proposal->user_name }} ?')">
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

                <hr>
                <br>
                <h6 class="mb-0">Penjadwalan Sidang Proposal</h6>
                <hr>
                <form action="{{ url('/admin/penjadwalan-sidang-proposal/add-jadwal-process') }}" method="post"
                    autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_kelompok" value="{{ $kelompok->id }}">
                    <input type="hidden" name="siklus_id" value="{{ $kelompok->id_siklus }}">
                    <input type="hidden" name="id_dosen_pembimbing_2" value="{{ $kelompok->id_dosen_pembimbing_2 }}">
                    <input type="hidden" name="id_dosen_penguji_1" value="{{ $kelompok->id_dosen_penguji_1 }}">
                    <input type="hidden" name="id_dosen_penguji_2" value="{{ $kelompok->id_dosen_penguji_2 }}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Waktu Mulai<span class="text-danger">*</span></label>

                                @if ($jadwal_sidang == null)
                                    <input placeholder="Atur waktu" id="waktu" type="text" class="form-control"
                                        name="waktu" required>
                                @else
                                    <input value="{{ $jadwal_sidang->waktu ? $jadwal_sidang->waktu : '' }}"
                                        placeholder="Atur waktu" id="waktu" type="text" class="form-control"
                                        name="waktu" required>
                                @endif

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Waktu Selesai<span class="text-danger">*</span></label>
                                @if ($jadwal_sidang == null)
                                    <input placeholder="Atur waktu" id="waktu_selesai" type="text" class="form-control"
                                        name="waktu_selesai" required>
                                @else
                                    <input value="{{ $jadwal_sidang->waktu_selesai ? $jadwal_sidang->waktu_selesai : '' }}"
                                        placeholder="Atur waktu" id="waktu_selesai" type="date" class="form-control"
                                        name="waktu_selesai" required>
                                @endif

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Pilih Ruang Sidang <span class="text-danger">*</span></label>
                                <select class="form-select select-2" name="ruangan_id" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($rs_ruang_sidang as $ruang_sidang)
                                        <option value="{{ $ruang_sidang->id }}">{{ $ruang_sidang->nama_ruang }} |
                                            {{ $ruang_sidang->kode_ruang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary float-end">Simpan</button>
                </form>

                <br>
                <h6>Validasi Dokumen C100</h6>

                <div class="card">
                    <h5 class="card-header">Dokumen C100</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <i class='bx bxs-file-doc bx-lg'></i>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" value="{{ $kelompok->file_name_c100 }}"
                                    readonly>
                                <a href="{{ url('/file/kelompok/c100') }}/{{ $kelompok->file_name_c100 }}"
                                    class="btn btn-primary float-end m-1 btn-sm">Download</a>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
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
                                        @if ($dosbing->jenis_dosen == 'Pembimbing 1')
                                            <td style="color: {{ $kelompok->status_pembimbing1_color }}">
                                                {{ $dosbing->status_dosen }}</td>
                                        @elseif ($dosbing->jenis_dosen == 'Pembimbing 2')
                                            <td style="color: {{ $kelompok->status_pembimbing2_color }}">
                                                {{ $dosbing->status_dosen }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
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

                <br>

            </div>

        </div>
    </div>

    <!-- Modal Dosen Penguji Proposal -->
    <div class="modal fade" id="Dosen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/admin/penjadwalan-sidang-proposal/add-dosen-penguji') }}" method="get"
                        autocomplete="off" id="dosbingForm">
                        <input type="hidden" name="id_kelompok" value="{{ $kelompok->id }}">
                        <select class="form-select" name="status_dosen" required id="statusSelect">
                            <option value="" disabled selected>-- Pilih Posisi--</option>
                            <option value="penguji 1">Penguji 1</option>
                            <option value="penguji 2">Penguji 2</option>
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
                            var dosbingArray = status === 'penguji 1' ? @json($rs_penguji) :
                                @json($rs_penguji);
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi flatpickr untuk elemen waktu_mulai dan waktu_selesai
            flatpickr('#waktu, #waktu_selesai', {
                dateFormat: 'Y-m-d H:i', // Format tanggal dan waktu (YYYY-MM-DD HH:mm)
                enableTime: true, // Izinkan pilihan waktu
                time_24hr: true, // Format waktu 24-jam
                minDate: new Date('2019-12-31'), // Batasi pilihan tanggal minimal ke hari ini
                maxDate: new Date('2050-12-31'), // Batasi pilihan tanggal maksimal
                defaultHour: 12, // Jam default jika tidak ada waktu terpilih
                defaultMinute: 0, // Menit default jika tidak ada waktu terpilih
                locale: {
                    buttons: {
                        now: 'Sekarang' // Mengganti teks tombol "Sekarang"
                    }
                },
                appendTo: document.body, // Append kalender ke dalam body
                inline: false, // Tidak menggunakan mode inline
                onChange: function(selectedDates, dateStr, instance) {
                    console.log('Tanggal dan waktu dipilih:', dateStr);
                }
            });
        });
    </script>
@endsection
