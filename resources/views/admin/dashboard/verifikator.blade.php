<!-- REKAPITULASI NILAI -->
@inject('dtid','App\Helpers\DateIndonesia')
<div class="card">
    <h5 class="card-header"> 
      Rekapitulasi Progres Penilaian Checker {{$dtid->get_month_year(date('Y-m-d'))}}
    </h5>

    <div class="card-body">    
      <div id="spinnerProgres1" class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>  
       <div id="chart-rekapitulasi-progres">

       </div>
    </div>
    <div class="card-footer">
      <a href="{{url('admin/verifikator/laporan/ronde')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
    </div>

</div>
<br>

<div class="card" >
  <h5 class="card-header"> 
    Rekapitulasi Nilai Checker {{$dtid->get_month_year(date('Y-m-d'))}}
  </h5>
  <div class="card-body">
    <div id="spinnerProgres2" class="d-flex justify-content-center mt-3">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>  
    <div class="row">
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 1
            </h5>
            <br>
             <div id="chart-rekapitulasi-nilai-1">
             </div>
      </div>
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 2
            </h5>
            <br>
             <div id="chart-rekapitulasi-nilai-2">
             </div>
      </div>
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 3
            </h5>
            <br>
             <div id="chart-rekapitulasi-nilai-3">
             </div>
      </div>
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 4
            </h5>
            <br>
             <div id="chart-rekapitulasi-nilai-4">
             </div>
      </div>
      {{-- <div class="card-footer">
        <a href="{{url('admin/verifikator/laporan/progres-checker')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
      </div> --}}
    </div>

  </div>
</div>
<br>


<br>
<!-- Capaian NILAI RS -->
<div class="card">
  <h5 class="card-header">Rekapitulasi Capaian Nilai Rumah Sakit Tahun {{date('Y')}}</h5>

  <div class="card-body">    
    <div id="spinnerProgres3" class="d-flex justify-content-center mt-3">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>  

     <div id="chart-capaian-nilai">

     </div>
  </div>
  {{-- <div class="card-footer">
    <a href="{{url('admin/validator/laporan/rekapitulasi-nilai')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
  </div> --}}
</div>
<br>

<div class="card">
  
  <h5 class="card-header"> 
    Rekapitulasi Parameter Rumah Sakit {{$dtid->get_month_year(date('Y-m-d'))}}
  </h5>

  <div class="card-body">    
    <div id="spinnerProgres4" class="d-flex justify-content-center mt-3">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>  

     <div id="chart-rekapitulasi-parameter">

     </div>
  </div>
</div>
<br>
{{-- progres pekerjaan  --}}
<div class="card">
    <h5 class="card-header"> 
      Rekapitulasi Progres Pekerjaan Checker {{$dtid->get_month_year($date)}}
    </h5>

    <div class="card-body">    
       <div id="chart-rekapitulasi-progres-pekerjaan">

       </div>
    </div>
    <div class="card-footer">
      <a href="{{url('admin/verifikator/laporan/pekerjaan')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
    </div>

</div>
<br>
{{-- PERBAIKAN  --}}
<div class="card" >
  <h5 class="card-header"> 
    Rekapitulasi Pekerjaan Perbaikan Checker {{$dtid->get_month_year(date('Y-m-d'))}}
  </h5>
  <div class="card-body">
    <div class="row">
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 1
            </h5>
            <br>
             <div id="chart-rekapitulasi-pekerjaan-perbaikan-1">
             </div>
      </div>
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 2
            </h5>
            <br>
             <div id="chart-rekapitulasi-pekerjaan-perbaikan-2">
             </div>
      </div>
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 3
            </h5>
            <br>
             <div id="chart-rekapitulasi-pekerjaan-perbaikan-3">
             </div>
      </div>
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 4
            </h5>
            <br>
             <div id="chart-rekapitulasi-pekerjaan-perbaikan-4">
             </div>
      </div>
    </div>

  </div>
</div>
{{-- pergantian  --}}
<br>
<div class="card" >
  <h5 class="card-header"> 
    Rekapitulasi Pekerjaan Pergantian Checker {{$dtid->get_month_year(date('Y-m-d'))}}
  </h5>
  <div class="card-body">
    <div class="row">
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 1
            </h5>
            <br>
             <div id="chart-rekapitulasi-pekerjaan-pergantian-1">
             </div>
      </div>
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 2
            </h5>
            <br>
             <div id="chart-rekapitulasi-pekerjaan-pergantian-2">
             </div>
      </div>
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 3
            </h5>
            <br>
             <div id="chart-rekapitulasi-pekerjaan-pergantian-3">
             </div>
      </div>
      <div class="col-md-3">
            <h5 class="text-center"> 
              Ronde 4
            </h5>
            <br>
             <div id="chart-rekapitulasi-pekerjaan-pergantian-4">
             </div>
      </div>

    </div>

  </div>
</div>


<script>
  $( document ).ready(function() {
    // rekapitulasiProgres(response.progres_checker);
    $("#spinnerProgres1").addClass('d-none');
    ajaxGetData();
    ajaxGetData2();
    ajaxGetDataPekerjaanPerbaikan();
    ajaxGetDataPekerjaanPergantian();
  }); 
  
  // get data
  var url = "{{ url('/admin/dashboard/ajax_dashboard_checker') }}";
  function ajaxGetData() {
      $.ajax({
          url: url,
          cache: false,
          method: "GET",
          success: function(response) {
            // console.log(response);
            
            rekatpitulasiNilaiR1(response.nilai_checker_R1)
            rekatpitulasiNilaiR2(response.nilai_checker_R2)
            rekatpitulasiNilaiR3(response.nilai_checker_R3)
            rekatpitulasiNilaiR4(response.nilai_checker_R4)

            $("#spinnerProgres2").addClass('d-none');
            
          }
      });
  }



  // get data
  var urlPekerjaan = "{{ url('/admin/dashboard/ajax_dashboard_checker_pekerjaan_perbaikan') }}";
  function ajaxGetDataPekerjaanPerbaikan() {
      $.ajax({
          url: urlPekerjaan,
          cache: false,
          method: "GET",
          success: function(response) {
            // console.log(response.pekerjaanR1);
            
            rekatpitulasiPekerjaanPerbaikan1(response.pekerjaanR1)
            rekatpitulasiPekerjaanPerbaikan2(response.pekerjaanR2)
            rekatpitulasiPekerjaanPerbaikan3(response.pekerjaanR3)
            rekatpitulasiPekerjaanPerbaikan4(response.pekerjaanR4)

            $("#spinnerProgresPekerjaan").addClass('d-none');
            
          }
      });
  }
    // get data
  var urlPekerjaanPergantian = "{{ url('/admin/dashboard/ajax_dashboard_checker_pekerjaan_pergantian') }}";
  function ajaxGetDataPekerjaanPergantian() {
      $.ajax({
          url: urlPekerjaanPergantian,
          cache: false,
          method: "GET",
          success: function(response) {
            console.log(response.pekerjaanR1);
            
            rekatpitulasiPekerjaanPergantian1(response.pekerjaanR1)
            rekatpitulasiPekerjaanPergantian2(response.pekerjaanR2)
            rekatpitulasiPekerjaanPergantian3(response.pekerjaanR3)
            rekatpitulasiPekerjaanPergantian4(response.pekerjaanR4)

            $("#spinnerProgresPekerjaanPergantian").addClass('d-none');
            
          }
      });
  }


  // get data2
  var url2 = "{{ url('/admin/dashboard/ajax_dashboard_checker_2') }}";
  function ajaxGetData2() {
      $.ajax({
          url: url2,
          cache: false,
          method: "GET",
          success: function(response) {
            // console.log(response.target_rata_nilai);
            rekapitulasiCapaian1Tahun(response.target_rata_nilai,response.nilai_bulan)
            rekapitulasiParameter(response.parameter_aman,response.parameter_bersih,response.parameter_rapih,response.parameter_tampak_baru,response.parameter_ramah_lingkungan)
            $("#spinnerProgres3").addClass('d-none');
            $("#spinnerProgres4").addClass('d-none');
          }
      });
  }
</script>

<!-- REKAPITULASI Progres -->
<script>
    var options = {
        series: [{
                    name: 'Progres',
                    data: @json($progres_checker)
                }
        ],
        chart: {
          type: 'bar',
          height: 350,
          toolbar: {
            tools: {
              download: false,
            }
          }
        },
        colors: ["#A5D6A7"],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          style: {
              colors: ["#595959"]
          },
          enabled: true,
          formatter: function (val) {
            return val + "%"
          }
          
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Ronde 1','Ronde 2','Ronde 3','Ronde 4',],
        },
        yaxis: {
          title: {
            text: 'Nilai dalam %'
          },
          min:0,
          max: 100
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };

        // render
        var chartRekapitulasiProgres = new ApexCharts(document.querySelector("#chart-rekapitulasi-progres"), options);
        chartRekapitulasiProgres.render();

</script>

<!-- REKAPITULASI Pekerjaan Progres -->
<script>

    var options = {
        series: [{
                    name: 'ProgresPekerjaan',
                    data: @json($progres_pekerjaan_checker)
                }
        ],
        chart: {
          type: 'bar',
          height: 350,
          toolbar: {
            tools: {
              download: false,
            }
          }
        },
        colors: ["#A5D6A7"],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          style: {
              colors: ["#595959"]
          },
          enabled: true,
          formatter: function (val) {
            return val + "%"
          }
          
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Ronde 1','Ronde 2','Ronde 3','Ronde 4',],
        },
        yaxis: {
          title: {
            text: 'Nilai dalam %'
          },
          min:0,
          max: 100
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };

        // render
        var chartRekapitulasiProgresPekerjaan = new ApexCharts(document.querySelector("#chart-rekapitulasi-progres-pekerjaan"), options);
        chartRekapitulasiProgresPekerjaan.render();

</script>

<!-- REKAPITULASI Nilai R1-->
<script>
  function rekatpitulasiNilaiR1(nilai_checker_R1) {
    var options = {
  
        series: nilai_checker_R1,
        labels: 
          ['Pembersihan', 'Perbaikan', 'Penggantian','Belum Dinilai'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300,
        },
        colors: ["#A5D6A7","#81D4FA","#EF9A9A","#dfdfdf"],
        dataLabels: {
            enabled: true,
            style: {
                colors: ["#595959"]
            },
            dropShadow: {
                enabled: false
            }
        },
        stroke: {
          show: true,
          width: 1,
  
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiNilai1 = new ApexCharts(document.querySelector("#chart-rekapitulasi-nilai-1"), options);
        chartRekapitulasiNilai1.render();
  }
</script>
<!-- REKAPITULASI Nilai R2-->
<script>
  function rekatpitulasiNilaiR2(nilai_checker_R2) {

    var options = {
        series: nilai_checker_R2,
        labels: ['Pembersihan', 'Perbaikan', 'Penggantian','Belum Dinilai'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300
        },
        colors: ["#A5D6A7","#81D4FA","#EF9A9A","#dfdfdf"],
  
        dataLabels: {
          enabled: true,
          style: {
                colors: ["#595959"]
          },
          dropShadow: {
              enabled: false
          }
        },
        stroke: {
          show: true,
          width: 1,
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiNilai2 = new ApexCharts(document.querySelector("#chart-rekapitulasi-nilai-2"), options);
        chartRekapitulasiNilai2.render();
  }
</script>
<!-- REKAPITULASI Nilai R3-->
<script>
  function rekatpitulasiNilaiR3(nilai_checker_R3) {

    var options = {
        series: nilai_checker_R3,
        labels: ['Pembersihan', 'Perbaikan', 'Penggantian','Belum Dinilai'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300
        },
        colors: ["#A5D6A7","#81D4FA","#EF9A9A","#dfdfdf"],
  
        dataLabels: {
          enabled: true,
          style: {
              colors: ["#595959"]
          },
          dropShadow: {
              enabled: false
          }
        },
        stroke: {
          show: true,
          width: 1,
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiNilai3 = new ApexCharts(document.querySelector("#chart-rekapitulasi-nilai-3"), options);
        chartRekapitulasiNilai3.render();
  }
</script>
<!-- REKAPITULASI Nilai R4-->
<script>
  function rekatpitulasiNilaiR4(nilai_checker_R4) {

    var options = {
        series: nilai_checker_R4,
        labels: ['Pembersihan', 'Perbaikan', 'Penggantian','Belum Dinilai'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300
        },
        colors: ["#A5D6A7","#81D4FA","#EF9A9A","#dfdfdf"],
  
        dataLabels: {
          enabled: true,
          textAnchor: 'middle',
          style: {
              colors: ["#595959"]
          },
          dropShadow: {
              enabled: false
          }
        },
        stroke: {
          show: true,
          width: 1,
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiNilai4 = new ApexCharts(document.querySelector("#chart-rekapitulasi-nilai-4"), options);
        chartRekapitulasiNilai4.render();
  }
</script>

{{-- ============== --}}
<!-- REKAPITULASI Pekerjaan perbaikan R1-->
<script>
  function rekatpitulasiPekerjaanPerbaikan1($seriesPekerjaan) {
    var options = {
        series: $seriesPekerjaan,
        labels: 
          ['Dikerjakan', 'Belum Dikerjakan'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300,
        },
        colors: ["#81D4FA","#EF9A9A"],
        dataLabels: {
            enabled: true,
            style: {
                colors: ["#595959"]
            },
            dropShadow: {
                enabled: false
            }
        },
        stroke: {
          show: true,
          width: 1,
  
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiPekerjaanPerbaikan1 = new ApexCharts(document.querySelector("#chart-rekapitulasi-pekerjaan-perbaikan-1"), options);
        chartRekapitulasiPekerjaanPerbaikan1.render();
  }
</script>
<!-- REKAPITULASI Pekerjaan perbaikan R2-->
<script>
  function rekatpitulasiPekerjaanPerbaikan2(seriesPekerjaan) {

    var options = {
        series: seriesPekerjaan,
        labels: ['Dikerjakan', 'Belum Dikerjakan'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300
        },
        colors: ["#81D4FA","#EF9A9A"],
  
        dataLabels: {
          enabled: true,
          style: {
                colors: ["#595959"]
          },
          dropShadow: {
              enabled: false
          }
        },
        stroke: {
          show: true,
          width: 1,
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiPekerjaanPerbaikan2 = new ApexCharts(document.querySelector("#chart-rekapitulasi-pekerjaan-perbaikan-2"), options);
        chartRekapitulasiPekerjaanPerbaikan2.render();
  }
</script>
<!-- REKAPITULASI Pekerjaan perbaikan R3-->
<script>
  function rekatpitulasiPekerjaanPerbaikan3(seriesPekerjaan) {

    var options = {
        series: seriesPekerjaan,
        labels: ['Dikerjakan', 'Belum Dikerjakan'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300
        },
        colors: ["#81D4FA","#EF9A9A"],
  
        dataLabels: {
          enabled: true,
          style: {
              colors: ["#595959"]
          },
          dropShadow: {
              enabled: false
          }
        },
        stroke: {
          show: true,
          width: 1,
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiPekerjaanPerbaikan3 = new ApexCharts(document.querySelector("#chart-rekapitulasi-pekerjaan-perbaikan-3"), options);
        chartRekapitulasiPekerjaanPerbaikan3.render();
  }
</script>
<!-- REKAPITULASI Pekerjaan perbaikan R4-->
<script>
  function rekatpitulasiPekerjaanPerbaikan4(seriesPekerjaan) {

    var options = {
        series: seriesPekerjaan,
        labels: ['Dikerjakan', 'Belum Dikerjakan'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300
        },
        colors: ["#81D4FA","#EF9A9A"],
  
        dataLabels: {
          enabled: true,
          textAnchor: 'middle',
          style: {
              colors: ["#595959"]
          },
          dropShadow: {
              enabled: false
          }
        },
        stroke: {
          show: true,
          width: 1,
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiPekerjaanPerbaikan4 = new ApexCharts(document.querySelector("#chart-rekapitulasi-pekerjaan-perbaikan-4"), options);
        chartRekapitulasiPekerjaanPerbaikan4.render();
  }
</script>

{{-- ============ --}}

{{-- ============== --}}
<!-- REKAPITULASI Pekerjaan pergantian R1-->
<script>
  function rekatpitulasiPekerjaanPergantian1($seriesPekerjaan) {
    var options = {
        series: $seriesPekerjaan,
        labels: 
          ['Dikerjakan', 'Belum Dikerjakan'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300,
        },
        colors: ["#81D4FA","#EF9A9A"],
        dataLabels: {
            enabled: true,
            style: {
                colors: ["#595959"]
            },
            dropShadow: {
                enabled: false
            }
        },
        stroke: {
          show: true,
          width: 1,
  
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiPekerjaanPergantian1 = new ApexCharts(document.querySelector("#chart-rekapitulasi-pekerjaan-pergantian-1"), options);
        chartRekapitulasiPekerjaanPergantian1.render();
  }
</script>
<!-- REKAPITULASI Pekerjaan pergantian R2-->
<script>
  function rekatpitulasiPekerjaanPergantian2(seriesPekerjaan) {

    var options = {
        series: seriesPekerjaan,
        labels: ['Dikerjakan', 'Belum Dikerjakan'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300
        },
        colors: ["#81D4FA","#EF9A9A"],
  
        dataLabels: {
          enabled: true,
          style: {
                colors: ["#595959"]
          },
          dropShadow: {
              enabled: false
          }
        },
        stroke: {
          show: true,
          width: 1,
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiPekerjaanPerbaikan2 = new ApexCharts(document.querySelector("#chart-rekapitulasi-pekerjaan-pergantian-2"), options);
        chartRekapitulasiPekerjaanPerbaikan2.render();
  }
</script>
<!-- REKAPITULASI Pekerjaan pergantian R3-->
<script>
  function rekatpitulasiPekerjaanPergantian3(seriesPekerjaan) {

    var options = {
        series: seriesPekerjaan,
        labels: ['Dikerjakan', 'Belum Dikerjakan'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300
        },
        colors: ["#81D4FA","#EF9A9A"],
  
        dataLabels: {
          enabled: true,
          style: {
              colors: ["#595959"]
          },
          dropShadow: {
              enabled: false
          }
        },
        stroke: {
          show: true,
          width: 1,
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiPekerjaanPergantian3 = new ApexCharts(document.querySelector("#chart-rekapitulasi-pekerjaan-pergantian-3"), options);
        chartRekapitulasiPekerjaanPergantian3.render();
  }
</script>
<!-- REKAPITULASI Pekerjaan pergantian R4-->
<script>
  function rekatpitulasiPekerjaanPergantian4(seriesPekerjaan) {

    var options = {
        series: seriesPekerjaan,
        labels: ['Dikerjakan', 'Belum Dikerjakan'],
        legend: {
          position: 'bottom',
        },
        chart: {
          type: 'pie',
          height: 300
        },
        colors: ["#81D4FA","#EF9A9A"],
  
        dataLabels: {
          enabled: true,
          textAnchor: 'middle',
          style: {
              colors: ["#595959"]
          },
          dropShadow: {
              enabled: false
          }
        },
        stroke: {
          show: true,
          width: 1,
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiPekerjaanPergantian4 = new ApexCharts(document.querySelector("#chart-rekapitulasi-pekerjaan-pergantian-4"), options);
        chartRekapitulasiPekerjaanPergantian4.render();
  }
</script>

{{-- ============ --}}

<!-- REKAPITULASI Capaian RS 1 Tahun -->
<script>
  function rekapitulasiCapaian1Tahun(target_rata_nilai,nilai_bulan){

  var options = {
      annotations: {
        yaxis: [
          {
            y: target_rata_nilai,
            borderColor: '#4ccc7f',
            strokeDashArray: 0,
            label: {
              borderColor: "#4ccc7f",
              style: {
                color: "#fff",
                background: "#4ccc7f",
                padding: {
                  left: 10,
                  right: 10,
                }
              },
              text: "Target: "+target_rata_nilai+"%"
            }
          }
        ]
      },
        series: [
            {
              name: "Nilai",
              data: nilai_bulan,
              
            },
          ],
        chart: {
          height: 350,
          type: 'line',
          zoom: {
            enabled: false
          },
          toolbar: {
            tools: {
              download: false,
            }
          }
        },
        colors: ["#64B5F6", "#81C784"],
        dataLabels: {
          enabled: true,

          formatter: function (val) {
            return val + "%"
          }
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          categories: ['Jan','Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Des'],
        },
        yaxis: {
          title: {
            text: 'Nilai dalam %'
          },
          min:0,
          max: 100
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };

        var chartCapaianNilai = new ApexCharts(document.querySelector("#chart-capaian-nilai"), options);
        chartCapaianNilai.render();
  }
</script>

<!-- REKAPITULASI Parameter  -->
<script>
  function rekapitulasiParameter(parameter_aman,parameter_bersih,parameter_rapih,parameter_tampak_baru,parameter_ramah_lingkungan){

    var options = {
        series: [{
                      name: 'Aman',
                      data: parameter_aman
                  }, {
                      name: 'Bersih',
                      data: parameter_bersih
                  }, {
                      name: 'Rapi',
                      data: parameter_rapih
                  }, {
                      name: 'Tampak Baru',
                      data: parameter_tampak_baru
                  },{
                      name: 'Ramah Lingkungan',
                      data: parameter_ramah_lingkungan
                  },
          ],
        chart: {
          type: 'bar',
          height: 350,
          toolbar: {
              tools: {
                download: false,
              }
            }
        },
        // colors: ["#4CCC7F"],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        colors: ["#A5D6A7","#81D4FA","#EF9A9A","#BDB4FF","#FFEE58"],
        dataLabels: {
          enabled: true,
          style: {
                colors: ["#595959"]
            },
            dropShadow: {
                enabled: false
            },
          formatter: function (val) {
            return val + "%"
          }
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Ronde 1','Ronde 2','Ronde 3','Ronde 4',],
        },
        yaxis: {
          title: {
            text: 'Nilai dalam %'
          },
          min:0,
          max: 100
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "%"
            }
          }
        }
      };
  
        // render
        var chartRekapitulasiParameter = new ApexCharts(document.querySelector("#chart-rekapitulasi-parameter"), options);
        chartRekapitulasiParameter.render();
  }
</script>
