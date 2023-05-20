<!-- inject helper date indonesia -->
@inject('dtid','App\Helpers\DateIndonesia')

@extends('admin.base.app')

@section('title')
    Dasboard 
@endsection

@section('content')
  <link rel="stylesheet" href="{{ asset('vendor/libs/apex-charts/apex-charts.css') }}" />
  <script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}"></script>
    
    {{-- <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">
        <div class="col-lg-12 mb-4 order-0">
          <div class="card">
            <div class="d-flex align-items-end row">
              <div class="col-sm-7">
                <div class="card-body">
                  <h5 class="card-title text-primary">Semangat Pagi {{ Auth::user()->user_name }}</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}


      <!-- SUPER ADMIN -->
      <!-- ------------------------------------------------------------------------------------ -->
     
    </div>
    

@endsection