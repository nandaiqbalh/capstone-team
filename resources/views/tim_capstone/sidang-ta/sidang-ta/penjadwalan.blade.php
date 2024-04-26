@extends('tim_capstone.base.app')

@section('title')
    Penjadwalan Sidang Tugas Akhir
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">Penjadwalan Sidang Tugas Akhir</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Data Mahasiswa</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/sidang-ta') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left"></i> Kembali</a>
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
                                <td>Nama Mahasiswa</td>
                                <td>:</td>

                                @if ($mahasiswa->user_name == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->user_name }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>NIM</td>
                                <td>:</td>

                                @if ($mahasiswa->nomor_induk == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->nomor_induk }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Nomor Kelompok</td>
                                <td>:</td>

                                @if ($mahasiswa->nomor_kelompok == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->nomor_kelompok }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Status Tugas Akhir</td>
                                <td>:</td>

                                @if ($mahasiswa->status_tugas_akhir == null)
                                    <td>-</td>
                                @else
                                    <td style="color: {{ $mahasiswa->status_sidang_color }}">
                                        {{ $mahasiswa->status_tugas_akhir }}</td>
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
                                <td>Judul Tugas Akhir</td>
                                <td>:</td>

                                @if ($mahasiswa->judul_ta_mhs == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->judul_ta_mhs }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Topik</td>
                                <td>:</td>

                                @if ($mahasiswa->nama_topik == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $mahasiswa->nama_topik }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
                <hr>

                <br>
                @if (count($rs_penguji_ta) >= 2)
                @else
                    <button type="button" class="btn btn-info btn-sm float-end" data-bs-toggle="modal"
                        data-bs-target="#Dosen">
                        Tambah Dosen Penguji TA
                    </button>
                @endif

                <h6 class="mb-0">List Dosen Penguji TA</h6>
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
                            @if ($rs_penguji_ta->count() > 0)
                                @foreach ($rs_penguji_ta as $index => $penguji_sidangta)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $penguji_sidangta->user_name }}</td>
                                        <td>{{ $penguji_sidangta->nomor_induk }}</td>
                                        <td>{{ $penguji_sidangta->jenis_dosen }}</td>
                                        @if ($penguji_sidangta->jenis_dosen == 'Penguji 1')
                                            <td style="color: {{ $mahasiswa->status_penguji1_color }}">
                                                {{ $penguji_sidangta->status_dosen }}</td>
                                        @elseif($penguji_sidangta->jenis_dosen == 'Penguji 2')
                                            <td style="color: {{ $mahasiswa->status_penguji2_color }}">
                                                {{ $penguji_sidangta->status_dosen }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td class="text-center">
                                            <a href="{{ url('/admin/sidang-ta/delete-dosen-penguji') }}/{{ $penguji_sidangta->user_id }}/{{ $mahasiswa->id_mahasiswa }}"
                                                class="btn btn-outline-danger btn-xs m-1 "
                                                onclick="return confirm('Apakah anda ingin menghapus {{ $penguji_sidangta->user_name }} ?')">
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
                <h6 class="mb-0">Penjadwalan Sidang TA</h6>
                <hr>
                <form action="{{ url('/admin/sidang-ta/add-jadwal-process') }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_mahasiswa" value="{{ $mahasiswa->id_mahasiswa }}">
                    <input type="hidden" name="id_kelompok_mhs" value="{{ $mahasiswa->id }}">
                    <input type="hidden" name="id_kelompok" value="{{ $mahasiswa->id_kelompok }}">
                    <input type="hidden" name="id_periode" value="{{ $periode_sidang_ta->id }}">
                    <input type="hidden" name="id_dosen_penguji_ta1" value="{{ $mahasiswa->id_dosen_penguji_ta1 }}">
                    <input type="hidden" name="id_dosen_penguji_ta2" value="{{ $mahasiswa->id_dosen_penguji_ta2 }}">

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
                                    <input placeholder="Atur waktu selesai" id="waktu_selesai" type="date"
                                        class="form-control" name="waktu_selesai" required>
                                @else
                                    <input value="{{ $jadwal_sidang->waktu_selesai ? $jadwal_sidang->waktu_selesai : '' }}"
                                        placeholder="Atur waktu selesai" id="waktu_selesai" type="date"
                                        class="form-control" name="waktu_selesai" required>
                                @endif

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Pilih Ruang Sidang <span class="text-danger">*</span></label>
                                <select class="form-select select-2" name="id_ruangan" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($rs_ruang_sidang as $ruang_sidang)
                                        <option value="{{ $ruang_sidang->id }}">{{ $ruang_sidang->nama_ruang }} |
                                            {{ $ruang_sidang->kode_ruang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn btn-primary float-end">Simpan</button>
                </form>

                <br>
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
                                            <td style="color: {{ $dosbing->status_pembimbing1_color }}">
                                                {{ $dosbing->status_dosen }}</td>
                                        @elseif ($dosbing->jenis_dosen == 'Pembimbing 2')
                                            <td style="color: {{ $dosbing->status_pembimbing2_color }}">
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
                    <form action="{{ url('/admin/sidang-ta/add-dosen-penguji') }}" method="get" autocomplete="off"
                        id="dosbingForm">
                        <input type="hidden" name="id_mahasiswa" value="{{ $mahasiswa->id_mahasiswa }}">
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
                        <br>
                        <button type="submit" class="btn btn-primary float-end">Simpan</button>
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
