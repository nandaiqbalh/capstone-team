
@extends('admin.base.app')

@section('title')
    Kelompok Mahasiswa
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Mahasiswa /</span> Kelompok Saya</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Kelompok</h5>
                    </div>

                    <div class="card-body">
                    @if ($kelompok != null)
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
                                        @if ($kelompok->nomor_kelompok==null)
                                        <td>Dalam Proses Peninjauan</td>
                                        @else
                                        <td>{{ $kelompok->nomor_kelompok }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Judul Capstone</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->judul_capstone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Topik</td>
                                        <td>:</td>
                                        <td>{{ $kelompok->nama_topik }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- list mahasiswa  --}}
                        <br>
                        <h5 class="mb-0">List Mahasiswa</h5>
                        <br>
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
                                    @if($rs_mahasiswa->count() > 0)
                                    @foreach($rs_mahasiswa as $index => $mahasiswa)
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
                                    @if($rs_dosbing->count() > 0)
                                    @foreach($rs_dosbing as $index => $dosbing)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $dosbing->user_name }}</td>
                                        <td>{{ $dosbing->nomor_induk }}</td>
                                        <td>{{ $dosbing->status_dosen }}</td>
                                        <td>{{ $dosbing->status_persetujuan }}</td>
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

                        {{-- list dos peng  --}}
                        @if ($proposal != null)
                        <br>
                        <h5 class="mb-0">Sidang Proposal</h5>
                        <br>

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
                                        <td>Tanggal dan Waktu</td>
                                        <td>:</td>
                                        <td>{{ $proposal->tanggal_mulai }} {{ $proposal->waktu }} </td>
                                    </tr>
                                    <tr>
                                        <td>Ruangan</td>
                                        <td>:</td>
                                        {{-- <td>{{ $proposal->ruangan }}</td> --}}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <h5 class="mb-0">List Dosen Penguji</h5>
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
                                    @if($rs_dospeng->count() > 0)
                                    @foreach($rs_dospeng as $index => $dosbing)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $dosbing->user_name }}</td>
                                        <td>{{ $dosbing->nomor_induk }}</td>
                                        <td>{{ $dosbing->status_dosen }}</td>
                                        <td>{{ $dosbing->status_persetujuan }}</td>
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

                    @else
                    <br>
                        <h5 class="mb-0">Anda Belum Memiliki Kelompok, Silahkan Daftar Terlebih dahulu</h5>
                    <br>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Daftar Individu</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Daftar Kelompok</button>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="{{ url('/mahasiswa/kelompok/add-kelompok-process') }}" method="post" autocomplete="off">
                                                            {{ csrf_field()}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label >Nama<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" value="{{ old('nama',$getAkun->user_name) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>NIM<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="nim" value="{{ old('nim',$getAkun->nomor_induk) }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label >Angkatan<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="angkatan" value="{{ old('angkatan',$getAkun->angkatan) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label >IPK<span class="text-danger">*</span></label>
                                        <input type="number" step='any' class="form-control" name="ipk" value="{{ old('ipk',$getAkun->ipk) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>SKS<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="sks" value="{{ old('sks',$getAkun->sks) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>No Telp<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="no_telp" value="{{ old('sks',$getAkun->no_telp) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Pilih Siklus <span class="text-danger">*</span></label>
                                        <select class="form-select select-2" name="id_siklus" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($rs_siklus as $siklus)
                                            <option value="{{$siklus->id}}">{{$siklus->tahun_ajaran}} | {{$siklus->tanggal_mulai}} sampai {{$siklus->tanggal_selesai}}</option>
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
                                            <option value="Laki - laki" @if( $getAkun->jenis_kelamin == 'Laki - laki' ) selected @endif>Laki - laki</option>
                                            <option value="Perempuan" @if( $getAkun->jenis_kelamin == 'Perempuan' ) selected @endif>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="{{$getAkun->user_email}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    Pilih Skala Prioritas Peminatan
                                    <table class="table">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Software & Database</th>
                                            <th scope="col">Embedded System & Robotics</th>
                                            <th scope="col">Computer Network & Security</th>
                                            <th scope="col">Multimedia & Game</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <th scope="row">1</th>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="s" id="exampleRadios1" value="1" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="e" id="exampleRadios1" value="1" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="c" id="exampleRadios1" value="1" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="m" id="exampleRadios1" value="1" ></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="s" id="exampleRadios2" value="2" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="e" id="exampleRadios2" value="2" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="c" id="exampleRadios2" value="2" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="m" id="exampleRadios2" value="2" ></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="s" id="exampleRadios3" value="3" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="e" id="exampleRadios3" value="3" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="c" id="exampleRadios3" value="3" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="m" id="exampleRadios3" value="3" ></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">4</th>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="s" id="exampleRadios4" value="4" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="e" id="exampleRadios4" value="4" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="c" id="exampleRadios4" value="4" ></td>
                                                <td style="text-align: center"><input class="form-check-input" type="radio" name="m" id="exampleRadios4" value="4" ></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    Pilih Skala Prioritas Topik
                                    <table class="table">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            @foreach ($rs_topik as $topik)
                                            <th scope="col">{{$topik->nama}}</th>
                                            @endforeach

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <th scope="row">1</th>
                                            @foreach ($rs_topik as $topik)
                                                <td style="text-align: center">
                                                    <input class="form-check-input" type="radio" name="{{$topik->id}}" id="topik{{$topik->id}}" value="1" >
                                                </td>
                                            @endforeach
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                            @foreach ($rs_topik as $topik)
                                                <td style="text-align: center">
                                                    <input class="form-check-input" type="radio" name="{{$topik->id}}" id="topik{{$topik->id}}" value="2" >
                                                </td>
                                            @endforeach
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                            @foreach ($rs_topik as $topik)
                                                <td style="text-align: center">
                                                    <input class="form-check-input" type="radio" name="{{$topik->id}}" id="topik{{$topik->id}}" value="3" >
                                                </td>
                                            @endforeach
                                            </tr>
                                            <tr>
                                                <th scope="row">4</th>
                                            @foreach ($rs_topik as $topik)
                                                <td style="text-align: center">
                                                    <input class="form-check-input" type="radio" name="{{$topik->id}}" id="topik{{$topik->id}}" value="4" >
                                                </td>
                                            @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                <br>
                                <button type="submit" class="btn btn-primary float-end">Daftar</button>
                            </form>
                        </div>


                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form action="{{ url('/mahasiswa/kelompok/add-punya-kelompok-process') }}" method="post" autocomplete="off">
                                {{ csrf_field()}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Dosbing 1 <span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="dosbing_1" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_dosbing as $dosbing)
                                                <option value="{{$dosbing->user_id}}">{{$dosbing->user_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Dosbing 2 </label>
                                            <select class="form-select select-2" name="dosbing_2">
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_dosbing as $dosbing)
                                                <option value="{{$dosbing->user_id}}">{{$dosbing->user_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label >Judul Capstone<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="judul_ta" value="{{ old('judul_ta') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Pilih Topik <span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="id_topik" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_topik as $topik)
                                                <option value="{{$topik->id}}">{{$topik->nama}}</option>
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
                                                <option value="{{$siklus->id}}">{{$siklus->tahun_ajaran}} | {{$siklus->tanggal_mulai}} sampai {{$siklus->tanggal_selesai}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <p>Nama Mahasiswa 1</p>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label >Nama<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama1" value="{{ old('nama1',$getAkun->user_name) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>NIM<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="nim1" value="{{$getAkun->nomor_induk}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label >Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="angkatan1" value="{{$getAkun->angkatan}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label >IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' class="form-control" name="ipk1" value="{{$getAkun->ipk}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="sks1" value="{{$getAkun->sks}}" required>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="no_telp1" value="{{$getAkun->no_telp}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                            <select class="form-select" name="jenis_kelamin1" required>
                                                <option value="" disabled selected>-- Pilih --</option>
                                                <option value="Laki - laki" @if( $getAkun->jenis_kelamin == 'Laki - laki' ) selected @endif>Laki - laki</option>
                                                <option value="Perempuan" @if( $getAkun->jenis_kelamin == 'Perempuan' ) selected @endif>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email1" value="{{$getAkun->user_email}}" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- mhs 2 --}}
                                <br>
                                <p>Nama Mahasiswa 2</p>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label >Nama<span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="nama2" >
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_mahasiswa as $mahasiswa)
                                                <option value="{{$mahasiswa->user_id}}">{{$mahasiswa->user_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>NIM<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="nim2" value="{{ old('nim2') }}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label >Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="angkatan2" value="{{ old('angkatan2') }}" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label >IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' class="form-control" name="ipk2" value="{{ old('ipk2') }}" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="sks2" value="{{ old('sks2') }}" >
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="no_telp2" value="{{ old('no_telp2') }}" >
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
                                            <input type="email" class="form-control" name="email2" value="" required>
                                        </div>
                                    </div>
                                </div>
                                    {{-- mhs 3  --}}
                                <br>
                                <p>Nama Mahasiswa 3</p>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label >Nama<span class="text-danger">*</span></label>
                                            <select class="form-select select-2" name="nama3" >
                                                <option value="" disabled selected>-- Pilih --</option>
                                                @foreach ($rs_mahasiswa as $mahasiswa)
                                                <option value="{{$mahasiswa->user_id}}">{{$mahasiswa->user_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>NIM<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="nim3" value="{{ old('nim3') }}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label >Angkatan<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="angkatan3" value="{{ old('angkatan3') }}" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label >IPK<span class="text-danger">*</span></label>
                                            <input type="number" step='any' class="form-control" name="ipk3" value="{{ old('ipk3') }}" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>SKS<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="sks3" value="{{ old('sks3') }}" >
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>No Telp<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="no_telp3" value="{{ old('no_telp3') }}" >
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
                                            <input type="email" class="form-control" name="email3" value="" required>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary float-end">Daftar</button>
                            </form>

                        </div>
                    </div>



                    @endif
                    </div>
                </div>
            </div>
@endsection
