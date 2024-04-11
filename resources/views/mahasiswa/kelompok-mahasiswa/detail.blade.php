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
                                <form id="form1" action="{{ url('/mahasiswa/kelompok/edit-kelompok-process') }}"
                                    method="post" autocomplete="off">
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
                                                <td>Status</td>
                                                <td>:</td>

                                                @if ($kelompok->status_kelompok != null)
                                                    <td style="color: {{ $kelompok->status_kelompok_color }};">
                                                        {{ $kelompok->status_kelompok }}
                                                    </td>
                                                @else
                                                    <td style="color: #F86F03;">
                                                        Menunggu Penetapan Kelompok!
                                                    </td>
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
                                                <td>Judul Capstone</td>
                                                <td>:</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="judul_capstone"
                                                                value="{{ old('judul_capstone', $kelompok->judul_capstone) }}"
                                                                placeholder="Judul Capstone" required>
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
                                                            <select class="form-select" name="topik" required>
                                                                <option value="" disabled selected>-- Pilih --
                                                                </option>
                                                                @foreach ($rs_topik as $topik)
                                                                    <option value="{{ $topik->id }}"
                                                                        @if ($topik->nama == $kelompok->nama_topik) selected @endif>
                                                                        {{ $topik->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="float-end">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#confirmModal" data-target-form="form1">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <hr>
                            {{-- list mahasiswa  --}}

                            <h6>List Mahasiswa</h6>

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
                            <h6>List Dosen Pembimbing</h6>

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
                                                <td class="text-center" colspan="5">Tidak ada data.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    @endif
                @elseif($rs_siklus == null)
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
                    <h6>Tidak ada siklus yang aktif!</h6>
                @elseif($periode_pendaftaran == null)
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
                    <h6>Tidak dalam periode pendaftaran capstone!</h6>
                @else
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
                            <form id="form2" action="{{ url('/mahasiswa/kelompok/add-kelompok-process') }}"
                                method="post" autocomplete="off">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_siklus" value="{{ $rs_siklus->id }}">

                                <h6>Detail Capstone</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Judul Capstone<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                placeholder="Masukkan usulan judul capstone" name="judul_capstone"
                                                value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Nama Siklus <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama_siklus"
                                                value="{{ $rs_siklus->nama_siklus }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <h6>Data Mahasiswa</h6>

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
                                            <input type="number" class="form-control"
                                                placeholder="Contoh: 21120120140051" name="nim"
                                                value="{{ old('nim', $getAkun->nomor_induk) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 2020"
                                                name="angkatan" value="{{ old('angkatan', $getAkun->angkatan) }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Jenis Kelamin<span class="text-danger">*</span></label>
                                                <input class="form-control" name="jenis_kelamin"
                                                    placeholder="Pilih jenis kelamin"
                                                    value="{{ old('jenis_kelamin', $getAkun->jenis_kelamin) }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="no_telp"
                                                placeholder="Contoh: 0831018123123"
                                                value="{{ old('no_telp', $getAkun->no_telp) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control"
                                                placeholder="Contoh: yusuf@gmail.com" name="email"
                                                value="{{ $getAkun->user_email }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' class="form-control"
                                                placeholder="Contoh: 3.87" name="ipk"
                                                value="{{ old('ipk', $getAkun->ipk) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="sks"
                                                placeholder="Contoh: 111" value="{{ old('sks', $getAkun->sks) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Data Peminatan Mahasiswa</h6>
                                        <div class="mb-3">
                                            <label>Skala Prioritas Peminatan<span class="text-danger">*</span></label>
                                            <p>1. Software & Database <br>
                                                2. Embedded System & Robotics <br>
                                                3. Computer Network & Security <br>
                                                4. Multimedia & Game</p>
                                            <input type="text" class="form-control" placeholder="Contoh: 4,2,3,1"
                                                name="peminatan" id="peminatan">
                                            <div id="peminatanValidationMessage" style="color: red;"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6>Data Topik Mahasiswa</h6>
                                        <div class="mb-3">
                                            <label>Skala Prioritas Topik<span class="text-danger">*</span></label>
                                            <p>1. Early Warning System <br>
                                                2. Building/area monitoring or controlling system <br>
                                                3. Smart business/orga...latform/support system <br>
                                                4. Smart city & transportation</p>
                                            <input type="text" class="form-control" placeholder="Contoh: 4,2,3,1"
                                                name="topik" id="topik">
                                            <div id="topikValidationMessage" style="color: red;"></div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal" data-target-form="form2">
                                    Simpan
                                </button>

                            </form>
                        </div>

                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form id="form3" action="{{ url('/mahasiswa/kelompok/add-punya-kelompok-process') }}"
                                method="post" autocomplete="off">
                                {{ csrf_field() }}
                                <h6>Data Detail Capstone</h6>
                                <input type="hidden" name="id_siklus" value="{{ $rs_siklus->id }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Topik <span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="id_topik">
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_topik as $topik)
                                                    <option value="{{ $topik->id }}">{{ $topik->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Judul Capstone<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="judul_capstone"
                                                value="{{ old('judul_capstone') }}"
                                                placeholder="Masukan usulan judul capstone">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label>Nama Siklus <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama_siklus"
                                                value="{{ $rs_siklus->nama_siklus }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Dosbing 1 <span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="dosbing_1">
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

                                </div>

                                <h6>Mahasiswa 1</h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Nama<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama1"
                                                placeholder="Contoh: Maulana Yusuf Suradin" readonly
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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="angkatan1" readonly
                                                value="{{ $getAkun->angkatan }}" placeholder="Contoh: 2020">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Jenis Kelamin<span class="text-danger">*</span></label>
                                                <input class="form-control" name="jenis_kelamin1"
                                                    placeholder="Pilih jenis kelamin"
                                                    value="{{ old('jenis_kelamin', $getAkun->jenis_kelamin) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="no_telp1"
                                                value="{{ $getAkun->no_telp }}" placeholder="Contoh: 0831018123123"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control"
                                                placeholder="Contoh: yusuf@gmail.com" name="email1" readonly
                                                value="{{ $getAkun->user_email }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' class="form-control" name="ipk1"
                                                value="{{ $getAkun->ipk }}" placeholder="Contoh: 3.87">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 111"
                                                name="sks1" value="{{ $getAkun->sks }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- mhs 2 --}}
                                <h6>Mahasiswa 2</h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Nama<span class="text-danger">*</span></label>
                                            <select id="selectNama2" class="form-select select-2" name="user_id2">
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
                                                value="{{ old('nim2') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 2020"
                                                name="angkatan2" value="{{ old('angkatan2') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Jenis Kelamin<span class="text-danger">*</span></label>
                                                <input class="form-control" name="jenis_kelamin2"
                                                    placeholder="Pilih jenis kelamin"
                                                    value="{{ old('jenis_kelamin', $getAkun->jenis_kelamin) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control"
                                                placeholder="Contoh: 0831018123123" name="no_telp2"
                                                value="{{ old('no_telp2') }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control"
                                                placeholder="Contoh: yusuf@gmail.com" name="email2" value=""
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' placeholder="Contoh: 3.87"
                                                class="form-control" name="ipk2" value="{{ old('ipk2') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 111"
                                                name="sks2" value="{{ old('sks2') }}">
                                        </div>
                                    </div>

                                </div>
                                {{-- mhs 3  --}}

                                <h6>Mahasiswa 3</h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Nama<span class="text-danger">*</span></label>
                                            <select id="selectNama3" class="form-select select-2" name="user_id3">
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
                                                value="{{ old('nim3') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 2020"
                                                readonly name="angkatan3" value="{{ old('angkatan3') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label>Jenis Kelamin<span class="text-danger">*</span></label>
                                                <input class="form-control" name="jenis_kelamin3"
                                                    placeholder="Pilih jenis kelamin"
                                                    value="{{ old('jenis_kelamin', $getAkun->jenis_kelamin) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control"
                                                placeholder="Contoh: 0831018123123" name="no_telp3"
                                                value="{{ old('no_telp3') }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control"
                                                placeholder="Contoh: yusuf@gmail.com" name="email3" value=""
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' class="form-control"
                                                placeholder="Contoh: 3.87" name="ipk3" value="{{ old('ipk3') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" placeholder="Contoh: 111"
                                                name="sks3" value="{{ old('sks3') }}">
                                        </div>
                                    </div>

                                </div>

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal" data-target-form="form3">
                                    Simpan
                                </button>
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

    <script>
        $(document).ready(function() {
            $('#selectNama2').change(function() {
                var selectedUserId = $(this).val(); // Mendapatkan nilai user_id yang dipilih

                // Menggunakan AJAX untuk mendapatkan data mahasiswa berdasarkan user_id
                // (Anda perlu menyesuaikan endpoint dan implementasi AJAX sesuai kebutuhan)
                $.ajax({
                    url: '/admin/mahasiswa/get-by-id/' +
                        selectedUserId, // Endpoint untuk mendapatkan data mahasiswa
                    type: 'GET',
                    success: function(response) {
                        // Mengisi nilai dari input lainnya berdasarkan data yang diterima dari AJAX response
                        $('input[name="nim2"]').val(response.data.nomor_induk);
                        $('input[name="angkatan2"]').val(response.data.angkatan);
                        $('select[name="jenis_kelamin2"]').val(response.data.jenis_kelamin);
                        $('input[name="no_telp2"]').val(response.data.no_telp);
                        $('input[name="email2"]').val(response.data.user_email);

                    },
                    error: function(xhr, status, error) {
                        console.log(
                            error); // Handle error jika terjadi kesalahan pada AJAX request
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#selectNama3').change(function() {
                var selectedUserId = $(this).val(); // Mendapatkan nilai user_id yang dipilih

                // Menggunakan AJAX untuk mendapatkan data mahasiswa berdasarkan user_id
                // (Anda perlu menyesuaikan endpoint dan implementasi AJAX sesuai kebutuhan)
                $.ajax({
                    url: '/admin/mahasiswa/get-by-id/' +
                        selectedUserId, // Endpoint untuk mendapatkan data mahasiswa
                    type: 'GET',
                    success: function(response) {
                        // Mengisi nilai dari input lainnya berdasarkan data yang diterima dari AJAX response
                        $('input[name="nim3"]').val(response.data.nomor_induk);
                        $('input[name="angkatan3"]').val(response.data.angkatan);
                        $('select[name="jenis_kelamin3"]').val(response.data.jenis_kelamin);
                        $('input[name="no_telp3"]').val(response.data.no_telp);
                        $('input[name="email3"]').val(response.data.user_email);

                    },
                    error: function(xhr, status, error) {
                        console.log(
                            error); // Handle error jika terjadi kesalahan pada AJAX request
                    }
                });
            });
        });
    </script>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin data yang Anda masukkan sudah sesuai?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                    <button type="button" class="btn btn-primary" id="confirmButton">Ya, Yakin</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var confirmButton = document.getElementById('confirmButton');

            // Listen for modal show event
            $('#confirmModal').on('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var formId = button.getAttribute('data-target-form');

                // Update the confirm button's click handler to submit the specific form
                confirmButton.addEventListener('click', function() {
                    // Find the form by ID and submit it
                    var form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    }
                });
            });

            // Clear the confirm button's click handler when the modal is hidden
            $('#confirmModal').on('hidden.bs.modal', function() {
                // Remove the click event listener from the confirmButton
                confirmButton.removeEventListener('click', null);
            });
        });
    </script>

@endsection
