<!-- inject helper date indonesia -->
@inject('dtid', 'App\Helpers\DateIndonesia')

@extends('tim_capstone.base.app')

@section('title')
    Beranda
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('vendor/libs/apex-charts/apex-charts.css') }}" />
    <script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Halo, {{ Auth::user()->user_name }}👋</h5>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <form action="{{ url('/dosen/beranda/filter-siklus') }}" method="get" autocomplete="off">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-8"> <!-- Menyesuaikan dengan lebar yang diinginkan -->
                                            <div>
                                                <select class="form-select select-2" name="id_siklus" required>
                                                    <option value="" disabled selected> -- Filter Berdasarkan Siklus
                                                        -- </option>
                                                    @foreach ($rs_siklus as $s)
                                                        <option value="{{ $s->id }}"
                                                            {{ isset($siklus) && $siklus->id == $s->id ? 'selected' : '' }}>
                                                            {{ $s->nama_siklus }}
                                                        </option>
                                                    @endforeach
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4 order-0">
                <a href="{{ url('dosen/kelompok-bimbingan') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Bimbingan</h5>
                                    <b>
                                        <span>{{ $rs_kelompok->jumlah_total_kelompok_dibimbing ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $rs_kelompok->jumlah_kelompok_tidak_aktif_dibimbing ?? 0 }}</b>
                                        Kelompok Telah Lulus
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $rs_kelompok->jumlah_kelompok_aktif_dibimbing ?? 0 }}</b>
                                        Kelompok Belum Lulus
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-6 mb-4 order-0">
                <a href="{{ url('dosen/mahasiswa-bimbingan') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Mahasiswa Bimbingan</h5>
                                    <b>
                                        <span>{{ $rs_mahasiswa->jumlah_total_mahasiswa_dibimbing ?? 0 }} Mahasiswa</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $rs_mahasiswa->jumlah_mahasiswa_tidak_aktif_dibimbing ?? 0 }}</b>
                                        Mahasiswa Telah Lulus
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $rs_mahasiswa->jumlah_mahasiswa_aktif_dibimbing ?? 0 }}</b>
                                        Mahasiswa Belum Lulus
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4 order-0">
                <a href="{{ url('dosen/pengujian-proposal') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Pengujian Sidang Proposal</h5>

                                    @if ($rs_pengujian_proposal != null)
                                        <span>Jadwal Terdekat:</span>
                                        <br>
                                        <b><span>{{ $rs_pengujian_proposal->hari_sidang }},
                                                {{ $rs_pengujian_proposal->waktu_sidang }} WIB</span></b>
                                        <span>({{ $rs_pengujian_proposal->nama_ruang }})</span>

                                        <hr>
                                        <span
                                            style="color: #44B158;"><b>{{ $rs_jumlah_sidang_proposal->jumlah_kelompok_tidak_aktif_dibimbing ?? 0 }}</b>
                                            Kelompok Lulus Sidang</span>
                                        <br>
                                        <span
                                            style="color: #F86F03;"><b>{{ $rs_jumlah_sidang_proposal->jumlah_kelompok_aktif_dibimbing ?? 0 }}</b>
                                            Kelompok Belum Sidang</span>
                                    @else
                                        <span>Jadwal Terdekat:</span>
                                        <br>
                                        <b><span>Belum ada jadwal</span></b>

                                        <hr>
                                        <span
                                            style="color: #44B158;"><b>{{ $rs_jumlah_sidang_proposal->jumlah_kelompok_tidak_aktif_dibimbing ?? 0 }}</b>
                                            Kelompok Lulus Sidang</span>
                                        <br>
                                        <span
                                            style="color: #F86F03;"><b>{{ $rs_jumlah_sidang_proposal->jumlah_kelompok_aktif_dibimbing ?? 0 }}</b>
                                            Kelompok Belum Sidang</span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-6 mb-4 order-0">
                <a href="{{ url('dosen/pengujian-ta') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Pengujian Sidang TA</h5>

                                    @if ($rs_pengujian_ta != null)
                                        <span>Jadwal Terdekat:</span>
                                        <br>
                                        <b><span>{{ $rs_pengujian_ta->hari_sidang }},
                                                {{ $rs_pengujian_ta->waktu_sidang }} WIB</span></b>
                                        <span>({{ $rs_pengujian_ta->nama_ruang }})</span>

                                        <hr>
                                        <span
                                            style="color: #44B158;"><b>{{ $rs_jumlah_sidang_ta->jumlah_mhs_tidak_aktif_dibimbing ?? 0 }}</b>
                                            Mahasiswa Lulus Sidang</span>
                                        <br>
                                        <span
                                            style="color: #F86F03;"><b>{{ $rs_jumlah_sidang_ta->jumlah_mhs_aktif_dibimbing ?? 0 }}</b>
                                            Mahasiswa Belum Sidang</span>
                                    @else
                                        <span>Jadwal Terdekat:</span>
                                        <br>
                                        <b><span>Belum ada jadwal</span></b>

                                        <hr>
                                        <span
                                            style="color: #44B158;"><b>{{ $rs_jumlah_sidang_ta->jumlah_mhs_tidak_aktif_dibimbing ?? 0 }}</b>
                                            Mahasiswa Lulus Sidang</span>
                                        <br>
                                        <span
                                            style="color: #F86F03;"><b>{{ $rs_jumlah_sidang_ta->jumlah_mhs_aktif_dibimbing ?? 0 }}</b>
                                            Mahasiswa Belum Sidang</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <!-- Pengumuman -->
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Pengumuman Terbaru</h5>
                        <div>
                            <div class="accordion" id="accordionExample">
                                @foreach ($rs_broadcast as $item)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">

                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne{{ $item->id }}" aria-expanded="false"
                                                aria-controls="collapseOne{{ $item->id }}">
                                                <div style="display: block;">
                                                    <span style="font-size: 1.2rem;">{{ $item->nama_event }}</span>
                                                    <br>
                                                    <span style="color: gray;">Diposting pada
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_date)->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapseOne{{ $item->id }}" class="accordion-collapse collapse"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                @if ($item->broadcast_image_name)
                                                    <img src="{{ asset('img/broadcast/' . $item->broadcast_image_name) }}"
                                                        style="max-width: 100%; max-height: 400px; border-radius: 10px; margin-bottom: 10px;">
                                                @endif
                                                <p>{!! $item->keterangan !!}</p>
                                                <br>
                                                @if ($item->link_pendukung != null)
                                                    <a href="http://{{ $item->link_pendukung }}"
                                                        class="btn btn-primary float-start" target="_blank">Lebih
                                                        lanjut</a>
                                                    <br>
                                                @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- pagination -->
        <div class="row mt-3 justify-content-between">
            <div class="col-auto mr-auto">
                <!-- Jumlah data yang ditampilkan -->
                <p>Menampilkan {{ $rs_broadcast->count() }} dari total {{ $rs_broadcast->total() }} pengumuman.</p>
            </div>
            <div class="col-auto">
                <!-- Tampilkan pagination -->
                {{ $rs_broadcast->links() }}
            </div>
        </div>

    </div>
@endsection
