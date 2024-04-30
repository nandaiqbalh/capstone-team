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
                                <form action="{{ url('/tim-capstone/beranda/filter-siklus') }}" method="get"
                                    autocomplete="off">
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
            <div class="col-lg-4 mb-4 order-0">
                <a href="{{ url('tim-capstone/validasi-kelompok') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Mendaftar</h5>
                                    <b>
                                        <span>{{ $kelompok->total_kelompok ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $kelompok->jumlah_kelompok_valid ?? 0 }}</b>
                                        Kelompok Valid
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $kelompok->jumlah_kelompok_tidak_valid ?? 0 }}</b>
                                        Kelompok Belum Valid
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 mb-4 order-0">
                <a href="{{ url('tim-capstone/kelompok-valid') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Mengunggah C100</h5>
                                    <b>
                                        <span>{{ $kelompok_c100->total_kelompok_file_c100 ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $kelompok_c100->total_kelompok_disetujui ?? 0 }}</b>
                                        Telah Disetujui
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $kelompok_c100->total_kelompok_belum_disetujui ?? 0 }}</b>
                                        Belum Disetujui
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 mb-4 order-0">
                <a href="{{ url('tim-capstone/penjadwalan-sidang-proposal') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Sidang Proposal</h5>
                                    <b>
                                        <span>{{ $kelompok_sidang_proposal->total_kelompok ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $kelompok_sidang_proposal->total_kelompok_sidang ?? 0 }}</b>
                                        Sudang Sidang
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $kelompok_sidang_proposal->total_kelompok_belum_sidang ?? 0 }}</b>
                                        Belum Sidang
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-4 mb-4 order-0">
                <a href="{{ url('tim-capstone/kelompok-valid') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Mengunggah C200</h5>
                                    <b>
                                        <span>{{ $kelompok_c200->total_kelompok_file_c200 ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $kelompok_c200->total_kelompok_disetujui ?? 0 }}</b>
                                        Telah Disetujui
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $kelompok_c200->total_kelompok_belum_disetujui ?? 0 }}</b>
                                        Belum Disetujui
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 mb-4 order-0">
                <a href="{{ url('tim-capstone/kelompok-valid') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Mengunggah C300</h5>
                                    <b>
                                        <span>{{ $kelompok_c300->total_kelompok_file_c300 ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $kelompok_c300->total_kelompok_disetujui ?? 0 }}</b>
                                        Telah Disetujui
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $kelompok_c300->total_kelompok_belum_disetujui ?? 0 }}</b>
                                        Belum Disetujui
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 mb-4 order-0">
                <a href="{{ url('tim-capstone/kelompok-valid') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Mengunggah C400</h5>
                                    <b>
                                        <span>{{ $kelompok_c400->total_kelompok_file_c400 ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $kelompok_c400->total_kelompok_disetujui ?? 0 }}</b>
                                        Telah Disetujui
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $kelompok_c400->total_kelompok_belum_disetujui ?? 0 }}</b>
                                        Belum Disetujui
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-4 mb-4 order-0">
                <a href="{{ url('tim-capstone/kelompok-valid') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Mengunggah C500</h5>
                                    <b>
                                        <span>{{ $kelompok_c500->total_kelompok_file_c500 ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $kelompok_c500->total_kelompok_disetujui ?? 0 }}</b>
                                        Telah Disetujui
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $kelompok_c500->total_kelompok_belum_disetujui ?? 0 }}</b>
                                        Belum Disetujui
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 mb-4 order-0">
                <a href="{{ url('tim-capstone/expo-project') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Mendaftar Expo</h5>
                                    <b>
                                        <span>{{ $kelompok_mendaftar_expo->total_kelompok ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $kelompok_mendaftar_expo->total_kelompok_mendaftar_expo ?? 0 }}</b>
                                        Telah Mendaftar
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $kelompok_mendaftar_expo->total_kelompok_belum_mendaftar_expo ?? 0 }}</b>
                                        Belum Mendaftar
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 mb-4 order-0">
                <a href="{{ url('tim-capstone/expo-project') }}">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Kelompok Lulus Expo</h5>
                                    <b>
                                        <span>{{ $kelompok_lulus_expo->total_kelompok ?? 0 }} Kelompok</span>
                                    </b>
                                    <hr>
                                    <span style="color: #44B158;">
                                        <b>{{ $kelompok_lulus_expo->total_kelompok_expo ?? 0 }}</b>
                                        Telah Lulus
                                    </span>
                                    <br>
                                    <span style="color: #F86F03;">
                                        <b>{{ $kelompok_lulus_expo->total_kelompok_belum_expo ?? 0 }}</b>
                                        Belum Lulus
                                    </span>
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
