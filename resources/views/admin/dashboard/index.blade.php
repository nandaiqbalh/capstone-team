<!-- inject helper date indonesia -->
@inject('dtid','App\Helpers\DateIndonesia')

@extends('admin.base.app')

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
                  <h5 class="card-title text-primary">Semangat Pagi {{ Auth::user()->user_name }}</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- SUPER ADMIN -->
      <!-- ------------------------------------------------------------------------------------ -->
      @if($role_id == '01')
      <div>
        @include('/admin/dashboard/super-admin')
      </div>
  
      <!-- HOLDING OPERASIONAL -->
      <!-- ------------------------------------------------------------------------------------ -->
      @elseif($role_id == '07')
      <div>
        @include('/admin/dashboard/holding-operasional')
      </div>

      <!-- HOLDING REGIONAL -->
      <!-- ------------------------------------------------------------------------------------ -->
      @elseif($role_id == '08')
      <div>
        @include('/admin/dashboard/holding-regional')
      </div>
  
  
      <!-- VALIDATOR -->
      <!-- ------------------------------------------------------------------------------------ -->
      @elseif($role_id == '05' || $role_id == '06')
      <div>
        @include('/admin/dashboard/validator')
      </div>
  
      <!-- VERIFIKATOR -->
      <!-- ------------------------------------------------------------------------------------ -->
      @elseif($role_id == '04' || $role_id == '03')
      <div>
        @include('/admin/dashboard/verifikator')
      </div>
  
      <!-- CHECKER -->
      <!-- ------------------------------------------------------------------------------------ -->
      @elseif($role_id == '02')
      <div>
        @include('/admin/dashboard/checker')
      </div>
  
  
      @endif

    </div>
    

@endsection