<!-- REKAPITULASI NILAI -->
<div class="card">
    <h5 class="card-header"> 
      Rekapitulasi Nilai Seluruh Rumah Sakit Tahun {{date('Y')}}
    </h5>

    <div class="card-body">
    
      <div id="spinner-chart-rekapitulasi-nilai" class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

       <div id="chart-rekapitulasi-nilai">

       </div>
    </div>
    <div class="card-footer">
      <a href="{{url('admin/holding-operasional/laporan/rekapitulasi-nilai')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
    </div>
</div>
<br>

<!-- REKAPITULASI NILAI TERTINGGI & TERENDAH -->
<div class="card">
    <!-- NILAI TERTINGGI -->
    <h5 class="card-header"> Rekapitulasi Nilai Tertinggi 3 Rumah Sakit Tahun {{date('Y')}}</h5>

    <div class="card-body">
      <div id="spinner-chart-rekapitulasi-nilai-tertinggi" class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

       <div id="chart-rekapitulasi-nilai-tertinggi">
          
       </div>
    </div>

    <br>
    <!-- NILAI TERTINGGI -->
    <h5 class="card-header"> Rekapitulasi Nilai Terendah 3 Rumah Sakit Tahun {{date('Y')}}</h5>

    <div class="card-body">  
      <div id="spinner-chart-rekapitulasi-nilai-terendah" class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>  

       <div id="chart-rekapitulasi-nilai-terendah">
          
       </div>
    </div>
    <div class="card-footer">
      <a href="{{url('admin/holding-operasional/laporan/rekapitulasi-nilai')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
    </div>

</div>
<br>

<!-- RATA RATA NILAI RS -->
<div class="card">
    <h5 class="card-header">Rekapitulasi Rata-Rata Nilai Seluruh Rumah Sakit Tahun {{date('Y')}}</h5>

    <div class="card-body">  
      <div id="spinner-chart-rata-rata-nilai" class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

       <div id="chart-rata-rata-nilai">

       </div>
    </div>
    <div class="card-footer">
      <a href="{{url('admin/holding-operasional/laporan/rata-rata-nilai')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
    </div>
</div>
<br>

<!-- REKAPITULASI PARAMETER -->
<div class="card">
    <h5 class="card-header"> Rekapitulasi Parameter Seluruh Rumah Sakit Tahun {{date('Y')}}</h5>

    <div class="card-body">
      <div id="spinner-chart-rekapitulasi-parameter" class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

       <div id="chart-rekapitulasi-parameter">

       </div>
    </div>
    <div class="card-footer">
      <a href="{{url('admin/holding-operasional/laporan/parameter-rumah-sakit')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
    </div>
</div>
<br>

<!-- REKAPITULASI TERLAMBAT SUBMIT -->
<div class="card">
  <h5 class="card-header"> 
    Rekapitulasi Terlambat Submit Seluruh Rumah Sakit Tahun {{date('Y')}}
  </h5>

  <div class="card-body">
      <div id="spinner-chart-rekapitulasi-terlambat-submit" class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>  

     <div id="chart-rekapitulasi-terlambat-submit">

     </div>
  </div>
  <div class="card-footer">
    <a href="{{url('admin/holding-operasional/laporan/terlambat-submit')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
  </div>
</div>
<br>

<!-- ------------------------------------------------------------------------------------------------------------------- -->
<!-- REKAPITULASI HASIL PEKERJAAN -->
<div class="card">
    <h5 class="card-header"> 
      Rekapitulasi Hasil Pekerjaan Seluruh Rumah Sakit Tahun {{date('Y')}}
    </h5>

    <div class="card-body">
        <div id="spinner-chart-rekapitulasi-hasil-pekerjaan" class="d-flex justify-content-center mt-3">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        
        <!-- semua -->
        <!-- <div id="chart-rekapitulasi-hasil-pekerjaan"></div> -->
        <br>
        <!-- perbaikan -->
        <div id="chart-rekapitulasi-hasil-pekerjaan-perbaikan"></div>
        <br>
        <!-- penggantian -->
        <div id="chart-rekapitulasi-hasil-pekerjaan-penggantian"></div>

    </div>

    <br>

    <div class="card-footer">
      <a href="{{url('admin/holding-operasional/laporan/hasil-pekerjaan-rumah-sakit')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
    </div>
</div>
<br>

<!-- REKAPITULASI Pekerjaan TERLAMBAT SUBMIT -->
<div class="card">
  <h5 class="card-header"> 
    Rekapitulasi Pekerjaan Terlambat Persetujuan Seluruh Rumah Sakit Tahun {{date('Y')}}
  </h5>

  <div class="card-body">
      <div id="spinner-chart-rekapitulasi-pekerjaan-terlambat-persetujuan" class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>  

     <div id="chart-rekapitulasi-pekerjaan-terlambat-persetujuan">

     </div>
  </div>
  <div class="card-footer">
    <a href="{{url('admin/holding-operasional/laporan/pekerjaan-terlambat-persetujuan')}}" class="btn btn-secondary btn-sm float-end">Lihat Selengkapnya</a>
  </div>
</div>
<br>

<!-- ---------------------------------------------------------------------------- -->
<!-- SCRIPT -->
<!-- ---------------------------------------------------------------------------- -->
<!-- AJAX -->
<script defer>
  // get data
  var url = "{{ url('/admin/dashboard/holding-operasional') }}";
  var data = [];
  function ajaxGetData() {

      $.ajax({
          url: url,
          cache: false,
          method: "GET",
          success: function(response) {
                  // rekapitulasi nilai
                  // rekapitulasiNilai(response.arr_rekapitulasi_nilai);
                  // nilai tertinggi
                  rekapitulasiNilaiTertinggi(response.arr_rekapitulasi_nilai_tertinggi_terendah);
                  // nilai terendah
                  rekapitulasiNilaiTerendah(response.arr_rekapitulasi_nilai_tertinggi_terendah);
                  // rekapitulasi rata nilai
                  rekapitulasiRataNilai(@json($arr_rekapitulasi_nilai));
                  // rekapitulasi parameter
                  rekapitulasiParameter(response.arr_rekapitulasi_parameter);
                  // terlambat submit
                  rekapitulasiTerlambatSubmit(response.arr_rekapitulasi_terlambat_submit);
          }
      });
  }

  // call ajax
  ajaxGetData();
</script>

<!-- REKAPITULASI NILAI -->
<script>

  var data = @json($arr_rekapitulasi_nilai);
  // remove spinner
  $("#spinner-chart-rekapitulasi-nilai").addClass('d-none');

  var options = {
      series: [{
                  name: 'Minimum',
                  data: data.min
              }, {
                  name: 'Maksimum',
                  data: data.max
              }, {
                  name: 'Nilai Tengah',
                  data: data.median
              }, {
                  name: 'Rata-rata',
                  data: data.average
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
      colors: ["#64B5F6", "#81C784","#FFB74D","#7986CB"],
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '100%',
          endingShape: 'rounded',
          dataLabels: {
            orientation: 'vertical',
            position: 'center' // bottom/center/top
          }
        },
      },
      dataLabels: {
        enabled: true,
        offsetY: 20,
        formatter: function (val) {
          return val + "%"
        },
        style: {
            fontSize: '10px',
        },
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
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
      var chartRekapitulasiNilai = new ApexCharts(document.querySelector("#chart-rekapitulasi-nilai"), options);
      chartRekapitulasiNilai.render();
  
</script>

<!-- REKAPITULASI NILAI 3 TERTINGGI -->
<script>
  
  function rekapitulasiNilaiTertinggi(data) {
    // remove spinner
    $("#spinner-chart-rekapitulasi-nilai-tertinggi").addClass('d-none');

    // data master
    const mastData =  data;
    
    var nilaiTertinggi1 = [];
    var nilaiTertinggi2 = [];
    var nilaiTertinggi3 = [];
    // cek jika master rs kosong
    if(mastData.length < 1) {
      for (let i = 0; i < 12; i++) {
        nilaiTertinggi1.push(0);
        nilaiTertinggi2.push(0);
        nilaiTertinggi3.push(0);
      }
    }
    else {
  
      mastData.tertinggi_1.forEach(function(item,index){
        nilaiTertinggi1.push(item.nilai);
      });
  
      mastData.tertinggi_2.forEach(function(item,index){
        nilaiTertinggi2.push(item.nilai);
      });
  
      mastData.tertinggi_3.forEach(function(item,index){
        nilaiTertinggi3.push(item.nilai);
      });
    }
  
    
      var options = {
          series: [{
                      name: 'Tertinggi ke-1',
                      data: nilaiTertinggi1
                  }, {
                      name: 'Tertinggi ke-2',
                      data: nilaiTertinggi2
                  }, {
                      name: 'Tertinggi ke-3',
                      data: nilaiTertinggi3
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
          colors: ["#64B5F6", "#81C784","#FFB74D"],
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '100%',
              endingShape: 'rounded',
              dataLabels: {
                orientation: 'vertical',
                position: 'center' // bottom/center/top
              }
            },
          },
          dataLabels: {
            enabled: true,
            offsetY: 20,
            formatter: function (val) {
              return val + "%"
            },
            style: {
                fontSize: '10px',
            },
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
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
          fill: {
            opacity: 1
          },
          tooltip: {
            y: {
              formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                var series_name = w.globals.seriesNames[seriesIndex];
                var index_data = dataPointIndex;
  
                // custom tooltip
                if(series_name == 'Tertinggi ke-1' ) {
                  var branch = mastData.tertinggi_1[index_data].branch;
                }
                else if(series_name == 'Tertinggi ke-2' ) {
                  var branch = mastData.tertinggi_2[index_data].branch;
                }
                else if(series_name == 'Tertinggi ke-3' ) {
                  var branch = mastData.tertinggi_3[index_data].branch;
                }
  
                return branch +' '+ val + "%"
              }
            }
          }
          };
  
          // render
          var chartRekapitulasiNilaiTertinggi = new ApexCharts(document.querySelector("#chart-rekapitulasi-nilai-tertinggi"), options);
          chartRekapitulasiNilaiTertinggi.render();
  }
</script>

<!-- REKAPITULASI NILAI 3 TERENDAH -->
<script>

  function rekapitulasiNilaiTerendah(data) {
    // remove spinner
    $("#spinner-chart-rekapitulasi-nilai-terendah").addClass('d-none');

    // data master
    const mastData2 =  data;
  
    var nilaiTerendah1 = [];
    var nilaiTerendah2 = [];
    var nilaiTerendah3 = [];
    
    // cek jika master rs kosong
    if(mastData2.length < 1) {
      for (let i = 0; i < 12; i++) {
        nilaiTerendah1.push(0);
        nilaiTerendah2.push(0);
        nilaiTerendah3.push(0);
      }
    }
    else {
      mastData2.terendah_1.forEach(function(item,index){
        nilaiTerendah1.push(item.nilai);
      });
  
      mastData2.terendah_2.forEach(function(item,index){
        nilaiTerendah2.push(item.nilai);
      });
  
      mastData2.terendah_3.forEach(function(item,index){
        nilaiTerendah3.push(item.nilai);
      });
    }
  
    
      var options = {
          series: [{
                      name: 'Terendah ke-1',
                      data: nilaiTerendah1
                  }, {
                      name: 'Terendah ke-2',
                      data: nilaiTerendah2
                  }, {
                      name: 'Terendah ke-3',
                      data: nilaiTerendah3
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
          colors: ["#64B5F6", "#81C784","#FFB74D"],
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '100%',
              endingShape: 'rounded',
              dataLabels: {
                orientation: 'vertical',
                position: 'center' // bottom/center/top
              }
            },
          },
          dataLabels: {
            enabled: true,
            offsetY: 20,
            formatter: function (val) {
              return val + "%"
            },
            style: {
                fontSize: '10px',
            },
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
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
          fill: {
            opacity: 1
          },
          tooltip: {
            y: {
              formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                var series_name = w.globals.seriesNames[seriesIndex];
                var index_data = dataPointIndex;
  
                // custom tooltip
                if(series_name == 'Terendah ke-1' ) {
                  var branch = mastData2.terendah_1[index_data].branch;
                }
                else if(series_name == 'Terendah ke-2' ) {
                  var branch = mastData2.terendah_2[index_data].branch;
                }
                else if(series_name == 'Terendah ke-3' ) {
                  var branch = mastData2.terendah_3[index_data].branch;
                }
  
                return branch +' '+ val + "%"
              }
            }
          }
          };
  
          // render
          var chartRekapitulasinilaiTerendah = new ApexCharts(document.querySelector("#chart-rekapitulasi-nilai-terendah"), options);
          chartRekapitulasinilaiTerendah.render();
  }
</script>

<!-- RATA - RATA NILAI -->
<script>
  function rekapitulasiRataNilai(data) {
    // remove spinner
    $("#spinner-chart-rata-rata-nilai").addClass('d-none');
  
    var options = {
          series: [{
                name: "Nilai",
                data: data.average
              }
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
            },
            style: {
                fontSize: '10px',
            },
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
          },
          annotations: {
            yaxis: [
                {
                y: '{{$target_rata_nilai}}',
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
                    text: "Target: {{$target_rata_nilai}}%"
                }
                }
            ]
          }
        };
  
          var chartRataNilai = new ApexCharts(document.querySelector("#chart-rata-rata-nilai"), options);
          chartRataNilai.render();
  }
</script>

<!-- REKAPITULASI PARAMETER -->
<script>
  function rekapitulasiParameter(data) {
    // remove spinner
    $("#spinner-chart-rekapitulasi-parameter").addClass('d-none');

    var options = {
        series: [{
                    name: 'Minimum',
                    data: data.min
                }, {
                    name: 'Maksimum',
                    data: data.max
                }, {
                    name: 'Nilai Tengah',
                    data: data.median
                }, {
                    name: 'Rata-rata',
                    data: data.average
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
        colors: ["#64B5F6", "#81C784","#FFB74D","#7986CB"],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '100%',
            endingShape: 'rounded',
            dataLabels: {
              orientation: 'vertical',
              position: 'center' // bottom/center/top
            }
          },
        },
        dataLabels: {
          enabled: true,
          offsetY: 20,
          formatter: function (val) {
            return val + "%"
          },
          style: {
              fontSize: '10px',
          },
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Aman','Bersih', 'Rapih', 'Tampak Baru', 'Ramah Lingkungan'],
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

<!-- REKAPITULASI TERLAMBAT SUBMIT -->
<script>
  function rekapitulasiTerlambatSubmit(data) {
    // remove spinner
    $("#spinner-chart-rekapitulasi-terlambat-submit").addClass('d-none');

    var max = '{{$max_terlambat_submit}}';
      
      var options = {
          series: [{
                      name: 'Ronde 1',
                      data: data.round_1
                  }, {
                      name: 'Ronde 2',
                      data: data.round_2
                  }, {
                      name: 'Ronde 3',
                      data: data.round_3
                  }, {
                      name: 'Ronde 4',
                      data: data.round_4
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
          colors: ["#64B5F6", "#81C784","#FFB74D","#7986CB"],
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '100%',
              endingShape: 'rounded',
              dataLabels: {
                orientation: 'vertical',
                position: 'center' // bottom/center/top
              }
            },
          },
          dataLabels: {
            enabled: true,
            offsetY: 20,
            formatter: function (val) {
              return val + "%"
            },
            style: {
                fontSize: '10px',
            },
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
          },
          xaxis: {
            categories: ['Jan','Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Des'],
          },
          yaxis: {
            title: {
              text: 'Jumlah'
            },
            min:0,
            max: parseInt(max)+5
          },
          fill: {
            opacity: 1
          },
          tooltip: {
            y: {
              formatter: function (val) {
                return val + ' Rumah Sakit'
              }
            }
          }
        };
  
          // render
          var chartRekapitulasiTS = new ApexCharts(document.querySelector("#chart-rekapitulasi-terlambat-submit"), options);
          chartRekapitulasiTS.render();
  }
</script>

<!-- ----------------------------------------------------- -->
<!-- REKAPITULASI HASIL PEKERJAAN-->
<script>
  //  get data
  $.ajax({
      url: "{{ url('/admin/dashboard/validator-ajax-data-pekerjaan') }}",
      cache: false,
      method: "GET",
      success: function(response) {
        // remove spinner
        $("#spinner-chart-rekapitulasi-hasil-pekerjaan").addClass('d-none');

        // var data = [
        //         {
        //           name : "Selesai",
        //           data : response.arr_selesai
        //         },
        //         {
        //           name : "Belum Dikerjakan",
        //           data : response.arr_belum_dikerjakan
        //         }
        //       ];

        // pekerjaanBar(data);
        
        // ----------------------------------------------------------------
        var perbaikan = [
                {
                  name : "Selesai",
                  data : response.arr_presentase_selesai_perbaikan
                },
                {
                  name : "Belum Dikerjakan",
                  data : response.arr_presentase_belum_dikerjakan_perbaikan
                }
              ];
        pekerjaanBarPerbaikan(perbaikan);

        // ----------------------------------------------------------------
        var penggantian = [
                {
                  name : "Selesai",
                  data : response.arr_presentase_selesai_penggantian
                },
                {
                  name : "Belum Dikerjakan",
                  data : response.arr_presentase_belum_dikerjakan_penggantian
                }
              ];
        pekerjaanBarPenggantian(penggantian);
      }
  });

  function pekerjaanBar(data){

    var options = {
        series : data,
        chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          stackType: '100%',
          toolbar: {
            tools: {
              download: false,
            }
          }
        },
        title : {
          text : 'Perbaikan & Penggantian'
        },
        dataLabels: {
          enabled: true,
          formatter: function(val, opt) {
              return val+'%'
          },
          offsetX: 0,
        },
        colors: ["#81C784","#E57373"],
        responsive: [
          {
            breakpoint: 480,
            options: {
              legend: {
                position: 'bottom',
                offsetX: -10,
                offsetY: 0
              }
            }
          }
        ],
        xaxis: {
          categories: ['Jan','Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Des'],
        },
        yaxis: {
          title: {
            text: 'Nilai dalam %'
          },
          min:0,
          max: 100,
          labels: {
            formatter: function(val) {
              return val.toFixed(0);
            }
          }
        },
        noData: {
            text: 'Menyiapkan data...'
        },
        fill: {
          opacity: 1
        },
        tooltip: {
            y: {
              formatter: function (val) {
                return val + ' %'
              }
            }
          }
      };
  
      var chartPekerjaan = new ApexCharts(document.querySelector("#chart-rekapitulasi-hasil-pekerjaan"), options);
      chartPekerjaan.render();
  }

  function pekerjaanBarPerbaikan(data){

    var options = {
        series : data,
        chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          stackType: '100%',
          toolbar: {
            tools: {
              download: false,
            }
          }
        },
        title : {
          text : 'Perbaikan'
        },
        dataLabels: {
          enabled: true,
          formatter: function(val, opt) {
              return val+'%'
          },
          offsetX: 0,
        },
        colors: ["#81C784","#E57373"],
        responsive: [
          {
            breakpoint: 480,
            options: {
              legend: {
                position: 'bottom',
                offsetX: -10,
                offsetY: 0
              }
            }
          }
        ],
        xaxis: {
          categories: ['Jan','Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Des'],
        },
        yaxis: {
          title: {
            text: 'Nilai dalam %'
          },
          min:0,
          max: 100,
          labels: {
            formatter: function(val) {
              return val.toFixed(0);
            }
          }
        },
        noData: {
            text: 'Menyiapkan data...'
        },
        fill: {
          opacity: 1
        },
        tooltip: {
            y: {
              formatter: function (val) {
                return val + ' %'
              }
            }
          }
      };

      var chartPekerjaanPerbaikan = new ApexCharts(document.querySelector("#chart-rekapitulasi-hasil-pekerjaan-perbaikan"), options);
      chartPekerjaanPerbaikan.render();
  }

  function pekerjaanBarPenggantian(data){

    var options = {
        series : data,
        chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          stackType: '100%',
          toolbar: {
            tools: {
              download: false,
            }
          }
        },
        title : {
          text : 'Penggantian'
        },
        dataLabels: {
          enabled: true,
          formatter: function(val, opt) {
              return val+'%'
          },
          offsetX: 0,
        },
        colors: ["#81C784","#E57373"],
        responsive: [
          {
            breakpoint: 480,
            options: {
              legend: {
                position: 'bottom',
                offsetX: -10,
                offsetY: 0
              }
            }
          }
        ],
        xaxis: {
          categories: ['Jan','Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Des'],
        },
        yaxis: {
          title: {
            text: 'Nilai dalam %'
          },
          min:0,
          max: 100,
          labels: {
            formatter: function(val) {
              return val.toFixed(0);
            }
          }
        },
        noData: {
            text: 'Menyiapkan data...'
        },
        fill: {
          opacity: 1
        },
        tooltip: {
            y: {
              formatter: function (val) {
                return val + ' %'
              }
            }
          }
      };

      var chartPekerjaanPenggantian = new ApexCharts(document.querySelector("#chart-rekapitulasi-hasil-pekerjaan-penggantian"), options);
      chartPekerjaanPenggantian.render();
  }
  
</script>

<!-- REKAPITULASI PEKERJAAN TERLAMBAT PERSETUJUAN -->
<script>
  //  get data
  $.ajax({
      url: "{{ url('/admin/dashboard/validator-ajax-data-pekerjaan-terlambat-persetujuan') }}",
      cache: false,
      method: "GET",
      success: function(response) {

          rekapitulasiPekerjaanTerlambatPersetujuan(response.arr_rekapitulasi_terlambat_persetujuan, response.max_terlambat_persetujuan);
      }
  });

  function rekapitulasiPekerjaanTerlambatPersetujuan(data, max_terlambat_persetujuan) {
    // remove spinner
    $("#spinner-chart-rekapitulasi-pekerjaan-terlambat-persetujuan").addClass('d-none');
      
      var options = {
          series: [{
                      name: 'Ronde 1',
                      data: data.round_1
                  }, {
                      name: 'Ronde 2',
                      data: data.round_2
                  }, {
                      name: 'Ronde 3',
                      data: data.round_3
                  }, {
                      name: 'Ronde 4',
                      data: data.round_4
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
          colors: ["#64B5F6", "#81C784","#FFB74D","#7986CB"],
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '55%',
              endingShape: 'rounded'
            },
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '80%',
              endingShape: 'rounded',
              dataLabels: {
                orientation: 'vertical',
                position: 'center' // bottom/center/top
              }
            },
          },
          dataLabels: {
            enabled: true,
            offsetY: 20,
            formatter: function (val) {
              return val + "%"
            },
            style: {
                fontSize: '10px',
            },
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
          },
          xaxis: {
            categories: ['Jan','Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Des'],
          },
          yaxis: {
            title: {
              text: 'Jumlah'
            },
            min:0,
            max: max_terlambat_persetujuan
          },
          fill: {
            opacity: 1
          },
          tooltip: {
            y: {
              formatter: function (val) {
                return val + ' Rumah Sakit'
              }
            }
          }
        };
  
          // render
          var chartRekapitulasiPTS = new ApexCharts(document.querySelector("#chart-rekapitulasi-pekerjaan-terlambat-persetujuan"), options);
          chartRekapitulasiPTS.render();
  }
</script>