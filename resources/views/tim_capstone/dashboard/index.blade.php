<!-- inject helper date indonesia -->
@inject('dtid','App\Helpers\DateIndonesia')

@extends('tim_capstone.base.app')

@section('title')
    Dasboard
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
  
    {{-- <div class="row">
      <div class="col-lg-4 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-12">
              <div class="card-body">
                <h5 class="card-title text-primary">Jadwal Pendaftaran Kelompok</h5>
                <ul class="list-group">
                  @if (count($rs_jad_kel) > 0)
                    @foreach ($rs_jad_kel as $item)
                    <li class="list-group-item">{{$item->tanggal_mulai}} s/d {{$item->tanggal_selesai}}</li>
                    @endforeach
                  @else
                  <li class="list-group-item">Jadwal Tidak Ada!</li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-12">
              <div class="card-body">
                <h5 class="card-title text-primary">Jadwal Sidang Proposal</h5>
                <ul class="list-group">
                  @if (count($rs_jad_sidang) > 0)
                    @foreach ($rs_jad_sidang as $item)
                    <li class="list-group-item">{{$item->tanggal_mulai}} {{$item->waktu}}</li>
                    @endforeach
                  @else
                  <li class="list-group-item">Jadwal Tidak Ada!</li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-12">
              <div class="card-body">
                <h5 class="card-title text-primary">Jadwal Expo</h5>
                <ul class="list-group">
                  @if (count($rs_jad_expo) > 0)
                    @foreach ($rs_jad_expo as $item)
                    <li class="list-group-item">{{$item->tanggal_mulai}} s/d {{$item->tanggal_selesai}}</li>
                    @endforeach
                  @else
                  <li class="list-group-item">Jadwal Tidak Ada!</li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> --}}

    <!-- Pengumuman -->
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-12">
          <div class="card-body">
            <h5 class="card-title text-primary">Pengumuman</h5>
            <div>
              <div class="accordion" id="accordionExample">
                @foreach ($rs_broadcast as $item)
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingOne">
                    {{-- <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$item->id}}" aria-expanded="false" aria-controls="collapseOne">
                      {{$item->nama_event}}
                    </button> --}}
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$item->id}}" aria-expanded="false" aria-controls="collapseOne{{$item->id}}">
                      <div style="display: block;">
                          <span style="font-size: 1.2rem;">{{$item->nama_event}}</span>
                          <br>
                          <span style="color: gray;">Diposting pada {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_date)->locale('id')->isoFormat('D MMMM YYYY') }}</span>


                      </div>
                    </button>
                  </h2>
                  <div id="collapseOne{{$item->id}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      @if ($item->broadcast_image_name)
                      <img src="{{ asset('img/broadcast/' . $item->broadcast_image_name) }}" style="max-width: 100%; max-height: 400px; border-radius: 10px; margin-bottom: 10px;">
                  @endif
                      <p>{{$item->keterangan}}
                        <br>
                        @if ($item->link_pendukung != null)
                        <a href="http://{{($item->link_pendukung)}}" class="btn btn-primary float-end">Link</a>
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
  </div>

@endsection
