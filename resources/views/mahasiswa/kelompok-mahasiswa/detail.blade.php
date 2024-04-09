@extends('tim_capstone.base.app')

@section('title')
    Kelompok
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Mahasiswa /</span> Kelompok</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Kelompok</h5>
            </div>

            <div class="card-body">
                @if ($kelompok != null)

                    @if ($siklus_sudah_punya_kelompok == null)

                        <h6>Siklus capstone sudah tidak aktif!</h6>
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

                                            @if ($kelompok->status_kelompok == null)
                                                <td>{{ $akun_mahasiswa->status_individu }}</td>
                                            @else
                                                <td>{{ $kelompok->status_kelompok }}</td>
                                            @endif
                                        </tr>
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
                                            <td>Topik</td>
                                            <td>:</td>
                                            @if ($kelompok->id == null)
                                                <td>-</td>
                                            @else
                                                <td>{{ $kelompok->nama_topik }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Judul Capstone</td>
                                            <td>:</td>
                                            @if ($kelompok->id == null)
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

                            {{-- list dos pem  --}}

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
                        @endif

                    @endif
                @elseif($rs_siklus == null)
                    <h6>Tidak ada siklus yang aktif!</h6>
                @elseif($periode_pendaftaran == null)
                    <h6>Belum memasuki periode pendaftaran capstone!</h6>
                @else
                    <h6>Anda Belum Memiliki Kelompok, Silahkan Daftar Terlebih dahulu</h6>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true">Daftar
                                Individu</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false">Daftar
                                Kelompok</button>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="{{ url('/mahasiswa/kelompok/add-kelompok-process') }}" method="post"
                                autocomplete="off">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label>Judul Capstone<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                placeholder="Masukkan usulan judul capstone" name="judul_capstone"
                                                value="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Nama<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                placeholder="Contoh: Maulana Yusuf Suradin" name="nama"
                                                value="{{ old('nama', $getAkun->user_name) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>NIM<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 21120120140051"
                                                name="nim" value="{{ old('nim', $getAkun->nomor_induk) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 2020"
                                                name="angkatan" value="{{ old('angkatan', $getAkun->angkatan) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' class="form-control"
                                                placeholder="Contoh: 3.87" name="ipk"
                                                value="{{ old('ipk', $getAkun->ipk) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="sks"
                                                placeholder="Contoh: 111" value="{{ old('sks', $getAkun->sks) }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="no_telp"
                                                placeholder="Contoh: 0831018123123"
                                                value="{{ old('sks', $getAkun->no_telp) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Siklus <span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="id_siklus" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_siklus as $siklus)
                                                    <option value="{{ $siklus->id }}">{{ $siklus->tahun_ajaran }} |
                                                        {{ $siklus->tanggal_mulai }} sampai
                                                        {{ $siklus->tanggal_selesai }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                            <select class="form-select" name="jenis_kelamin" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                <option value="Laki - laki"
                                                    @if ($getAkun->jenis_kelamin == 'Laki - laki') selected @endif>Laki - laki
                                                </option>
                                                <option value="Perempuan"
                                                    @if ($getAkun->jenis_kelamin == 'Perempuan') selected @endif>Perempuan
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control"
                                                placeholder="Contoh: yusuf@gmail.com" name="email"
                                                value="{{ $getAkun->user_email }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Skala Prioritas Peminatan<span class="text-danger">*</span></label>
                                            <p>1. Software & Database <br>
                                                2. Embedded System & Robotics <br>
                                                3. Computer Network & Security <br>
                                                4. Multimedia & Game</p>
                                            <input type="text" class="form-control" placeholder="Contoh: 4,2,3,1"
                                                name="peminatan" id="peminatan" required>
                                            <div id="peminatanValidationMessage" style="color: red;"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Skala Prioritas Topik<span class="text-danger">*</span></label>
                                            <p>1. Early Warning System <br>
                                                2. Building/area monitoring or controlling system <br>
                                                3. Smart business/organization platform/support system <br>
                                                4. Smart city & transportation</p>
                                            <input type="text" class="form-control" placeholder="Contoh: 4,2,3,1"
                                                name="topik" id="topik" required>
                                            <div id="topikValidationMessage" style="color: red;"></div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" id="submitButton" class="btn btn-primary float-end"
                                    disabled>Daftar</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form action="{{ url('/mahasiswa/kelompok/add-punya-kelompok-process') }}" method="post"
                                autocomplete="off">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Dosbing 1 <span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="dosbing_1" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_dosbing1 as $dosbing)
                                                    <option value="{{ $dosbing->user_id }}">{{ $dosbing->user_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Dosbing 2 </label>
                                            <select class="form-select select-2" name="dosbing_2">
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_dosbing2 as $dosbing)
                                                    <option value="{{ $dosbing->user_id }}">{{ $dosbing->user_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Judul Capstone<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="judul_capstone"
                                                value="{{ old('judul_capstone') }}"
                                                placeholder="Masukan usulan judul capstone" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Topik <span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="id_topik" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_topik as $topik)
                                                    <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Siklus <span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="id_siklus" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_siklus as $siklus)
                                                    <option value="{{ $siklus->id }}">{{ $siklus->tahun_ajaran }} |
                                                        {{ $siklus->tanggal_mulai }} sampai
                                                        {{ $siklus->tanggal_selesai }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <p>Nama Mahasiswa 1</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Nama<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama1"
                                                placeholder="Contoh: Maulana Yusuf Suradin"
                                                value="{{ old('nama1', $getAkun->user_name) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>NIM<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="nim1"
                                                value="{{ $getAkun->nomor_induk }}" placeholder="Contoh: 21120120140051"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="angkatan1"
                                                value="{{ $getAkun->angkatan }}" placeholder="Contoh: 2020" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' class="form-control" name="ipk1"
                                                value="{{ $getAkun->ipk }}" placeholder="Contoh: 3.87" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 111"
                                                name="sks1" value="{{ $getAkun->sks }}" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="no_telp1"
                                                value="{{ $getAkun->no_telp }}" placeholder="Contoh: 0831018123123"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                            <select class="form-select" name="jenis_kelamin1" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                <option value="Laki - laki"
                                                    @if ($getAkun->jenis_kelamin == 'Laki - laki') selected @endif>Laki - laki
                                                </option>
                                                <option value="Perempuan"
                                                    @if ($getAkun->jenis_kelamin == 'Perempuan') selected @endif>Perempuan
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control"
                                                placeholder="Contoh: yusuf@gmail.com" name="email1"
                                                value="{{ $getAkun->user_email }}" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- mhs 2 --}}

                                <p>Nama Mahasiswa 2</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Nama<span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="user_id2">
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_mahasiswa as $mahasiswa)
                                                    <option value="{{ $mahasiswa->user_id }}">
                                                        {{ $mahasiswa->user_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>NIM<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control"
                                                placeholder="Contoh: 21120120140051" name="nim2"
                                                value="{{ old('nim2') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 2020"
                                                name="angkatan2" value="{{ old('angkatan2') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' placeholder="Contoh: 3.87"
                                                class="form-control" name="ipk2" value="{{ old('ipk2') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 111"
                                                name="sks2" value="{{ old('sks2') }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control"
                                                placeholder="Contoh: 0831018123123" name="no_telp2"
                                                value="{{ old('no_telp2') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                            <select class="form-select" name="jenis_kelamin2" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                <option value="Laki - laki">Laki - laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control"
                                                placeholder="Contoh: yusuf@gmail.com" name="email2" value=""
                                                required>
                                        </div>
                                    </div>
                                </div>
                                {{-- mhs 3  --}}

                                <p>Nama Mahasiswa 3</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Nama<span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="user_id3">
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_mahasiswa as $mahasiswa)
                                                    <option value="{{ $mahasiswa->user_id }}">
                                                        {{ $mahasiswa->user_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>NIM<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control"
                                                placeholder="Contoh: 21120120140051" name="nim3"
                                                value="{{ old('nim3') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 2020"
                                                name="angkatan3" value="{{ old('angkatan3') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' class="form-control"
                                                placeholder="Contoh: 3.87" name="ipk3" value="{{ old('ipk3') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 111"
                                                name="sks3" value="{{ old('sks3') }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control"
                                                placeholder="Contoh: 0831018123123" name="no_telp3"
                                                value="{{ old('no_telp3') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                            <select class="form-select" name="jenis_kelamin3" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                <option value="Laki - laki">Laki - laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control"
                                                placeholder="Contoh: yusuf@gmail.com" name="email3" value=""
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary float-end">Daftar</button>
                            </form>

                        </div>
                    </div>

                @endif
            </div>
        </div>
    </div>

    <script>
        function validateInput(input, validationMessageId) {
            var inputArray = input.split(",").map(Number);
            var validationMessage = document.getElementById(validationMessageId);

            // Validasi panjang input harus 4
            if (inputArray.length !== 4) {
                validationMessage.textContent = "Input harus terdiri dari 4 angka.";
                return false;
            }

            // Validasi angka harus antara 1-4 dan harus unik
            for (var i = 0; i < inputArray.length; i++) {
                if (inputArray[i] < 1 || inputArray[i] > 4 || inputArray.indexOf(inputArray[i]) !== i) {
                    validationMessage.textContent = "Input harus berisi angka antara 1-4 dan harus unik.";
                    return false;
                }
            }

            validationMessage.textContent = "";
            return true;
        }

        document.getElementById("peminatan").addEventListener("change", function() {
            var input = this.value.trim();
            var isValid = validateInput(input, "peminatanValidationMessage");
            var submitButton = document.getElementById("submitButton");
            submitButton.disabled = !isValid;
        });

        document.getElementById("topik").addEventListener("change", function() {
            var input = this.value.trim();
            var isValid = validateInput(input, "topikValidationMessage");
            var submitButton = document.getElementById("submitButton");
            submitButton.disabled = !isValid;
        });
    </script>
@endsection
