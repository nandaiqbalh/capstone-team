@inject('dtid','App\Helpers\DateIndonesia')
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Lampiran ({{$opt}}) Executive Report Checklist Pengawasan Program ABRT-RL {{$laporan_ronde->branch_name}} {{$laporan_ronde->round_name}} Bulan {{$dtid->get_month_year($laporan_ronde->created_date)}}</title>
  <style>
    
    body {
        display: flex;
        justify-content: center;
        font-family: Arial, Helvetica, sans-serif;
    }
    .page {
         max-width: 330mm;
         padding: 5px;
    }

    .title {
        text-align: center;
    }

    .content {
        padding: 2px;
        font-size: 10pt;
    }

    .block-1 ol li {
        page-break-after: always;
    }

    .block-1 ol li:last-child {
        page-break-after: avoid;
    }

    .block-1 img {
        width: 150px;
        height: 84px;
        vertical-align: top;
    }


    table {
        border-collapse: collapse;
        width: 100%;
    }

    table thead {
        background-color: rgb(235, 235, 235);
    }

    table tbody tr {
        vertical-align: baseline;
    }

    table td, table th {
        border: 1px solid black;
        padding: 3px;
        font-size: 10pt;
    }

    @page { 
        size: 21cm 33cm landscape;
        margin: 1cm; 
    }

  </style>
</head>

<body >
  <div class="page">
      <div class="content">
            <div class="block-1">
                <div class="title">
                    <h3>
                        Lampiran ({{$opt}})
                        <br>
                        Executive Report Checklist Pengawasan Program ABRT-RL
                        <br>
                        {{$laporan_ronde->branch_name}}
                        <br>
                        {{$laporan_ronde->round_name}} Bulan {{$dtid->get_month_year($laporan_ronde->created_date)}}
                    </h3>
                </div>

                <br>
                <ol type="A" start="{{$ol_start}}">
                    @if(in_array("A", explode(",", $opt)))
                    <li style="margin-top: 20px; ">
                        Pembersihan
                        <br>
                        <br>
                        <table id="table-pembersihan">
                            <thead>
                                <tr style="text-align: center;">
                                    <th  width="5%">No</th>
                                    <th  width="10%">Area</th>
                                    <th  width="10%">Sub Area</th>
                                    <th  width="15%">Item Penilaian</th>
                                    <th  width="20%">Komponen Penilaian</th>
                                    <th  width="10%">Parameter</th>
                                    <th  width="15%">Keterangan</th>
                                    <th  width="15%">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($rs_pembersihan->count() > 0)
                                    @foreach($rs_pembersihan as $index => $pembersihan)
                                        <tr>
                                            <td  style="text-align:center;">{{$index+1}}.</td>
                                            <td  >{{ $pembersihan->area }}</td>
                                            <td >{{ $pembersihan->sub_area }}</td>
                                            <td  >{{ $pembersihan->item }} #{{$pembersihan->unique_id}}</td>

                                            <!-- komponen first element -->
                                            @if($pembersihan->rs_komponen->count() > 0)
                                            <td>
                                                1. {{$pembersihan->rs_komponen[0]->name}}
                                            </td>
                                            <td style="text-align: center;">{{$pembersihan->rs_komponen[0]->parameter}}</td>
                                            <td>{{$pembersihan->rs_komponen[0]->description}}</td>
                                            <td style="text-align: center;">
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$pembersihan->rs_komponen[0]->img_name}}/150/84" alt="foto" loading="lazy">
                                            </td>
                                            @else
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            @endif
                                        </tr>

                                        <!-- loop komponen -->
                                        @foreach($pembersihan->rs_komponen as $index2 => $komponen)
                                            <!-- skip first element -->
                                            @if ($loop->first)
                                                @continue
                                            @endif

                                            <tr>
                                                <td  ></td>
                                                <td ></td>
                                                <td ></td>
                                                <td ></td>
                                                <td >
                                                    {{$index2+1}}. {{$komponen->name}}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{$komponen->parameter}}
                                                </td>
                                                <td >
                                                    {{$komponen->description}}
                                                </td>
                                                <td style="text-align: center;">
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name}}/150/84" alt="foto" loading="lazy">
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" style="text-align: center;">Tidak ada data.</td>
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </li>
                    @endif

                    @if(in_array("B", explode(",", $opt)))
                    <li style="margin-top: 20px;">
                        Perbaikan
                        <br>
                        <br>
                        <table>
                            <thead>
                                <tr style="text-align: center;">
                                    <th  width="5%">No</th>
                                    <th  width="10%">Area</th>
                                    <th  width="10%">Sub Area</th>
                                    <th  width="15%">Item Penilaian</th>
                                    <th  width="20%">Komponen Penilaian</th>
                                    <th  width="10%">Parameter</th>
                                    <th  width="15%">Keterangan</th>
                                    <th  width="15%">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($rs_perbaikan->count() > 0)
                                    @foreach($rs_perbaikan as $index => $perbaikan)
                                        <tr>
                                            <td  style="text-align:center;">{{$index+1}}.</td>
                                            <td  >{{ $perbaikan->area }}</td>
                                            <td >{{ $perbaikan->sub_area }}</td>
                                            <td  >{{ $perbaikan->item }} #{{$perbaikan->unique_id}}</td>

                                            <!-- komponen first element -->
                                            @if($perbaikan->rs_komponen->count() > 0)
                                            <td>
                                                1. {{$perbaikan->rs_komponen[0]->name}}
                                            </td>
                                            <td style="text-align: center;">{{$perbaikan->rs_komponen[0]->parameter}}</td>
                                            <td>{{$perbaikan->rs_komponen[0]->description}}</td>
                                            <td style="text-align: center;">
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$perbaikan->rs_komponen[0]->img_name}}/150/84" alt="foto" loading="lazy">
                                            </td>
                                            @else
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            @endif
                                        </tr>

                                        <!-- loop komponen -->
                                        @foreach($perbaikan->rs_komponen as $index2 => $komponen)
                                            <!-- skip first element -->
                                            @if ($loop->first)
                                                @continue
                                            @endif

                                            <tr>
                                                <td ></td>
                                                <td ></td>
                                                <td ></td>
                                                <td ></td>
                                                <td >
                                                    {{$index2+1}}. {{$komponen->name}}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{$komponen->parameter}}
                                                </td>
                                                <td >
                                                    {{$komponen->description}}
                                                </td>
                                                <td style="text-align: center;">
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name}}/150/84" alt="foto" loading="lazy">
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" style="text-align: center;">Tidak ada data.</td>
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </li>
                    @endif
                    
                    @if(in_array("C", explode(",", $opt)))
                    <li style="margin-top: 20px;">
                        Penggantian
                        <br>
                        <br>
                        <table>
                            <thead>
                                <tr style="text-align: center;">
                                    <th  width="5%">No</th>
                                    <th  width="10%">Area</th>
                                    <th  width="10%">Sub Area</th>
                                    <th  width="15%">Item Penilaian</th>
                                    <th  width="20%">Komponen Penilaian</th>
                                    <th  width="10%">Parameter</th>
                                    <th  width="15%">Keterangan</th>
                                    <th  width="15%">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($rs_penggantian->count() > 0)
                                    @foreach($rs_penggantian as $index => $penggantian)
                                        <tr>
                                            <td  style="text-align:center;">{{$index+1}}.</td>
                                            <td  >{{ $penggantian->area }}</td>
                                            <td >{{ $penggantian->sub_area }}</td>
                                            <td  >{{ $penggantian->item }} #{{$penggantian->unique_id}}</td>

                                            <!-- komponen first element -->
                                            @if($penggantian->rs_komponen->count() > 0)
                                            <td>
                                                1. {{$penggantian->rs_komponen[0]->name}}
                                            </td>
                                            <td style="text-align: center;">{{$penggantian->rs_komponen[0]->parameter}}</td>
                                            <td>{{$penggantian->rs_komponen[0]->description}}</td>
                                            <td style="text-align: center;">
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$penggantian->rs_komponen[0]->img_name}}/150/84" alt="foto" loading="lazy">
                                            </td>
                                            @else
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            @endif
                                        </tr>

                                        <!-- loop komponen -->
                                        @foreach($penggantian->rs_komponen as $index2 => $komponen)
                                            <!-- skip first element -->
                                            @if ($loop->first)
                                                @continue
                                            @endif

                                            <tr>
                                                <td  ></td>
                                                <td ></td>
                                                <td ></td>
                                                <td ></td>
                                                <td >
                                                    {{$index2+1}}. {{$komponen->name}}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{$komponen->parameter}}
                                                </td>
                                                <td >
                                                    {{$komponen->description}}
                                                </td>
                                                <td style="text-align: center;">
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name}}/150/84" alt="foto" loading="lazy">
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" style="text-align: center;">Tidak ada data.</td>
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </li>
                    @endif

                    @if(in_array("D", explode(",", $opt)))
                    <li style="margin-top: 20px;">
                        Belum Dinilai
                        <br>
                        <br>
                        <table>
                            <thead>
                                <tr style="text-align: center;">
                                    <th  width="5%">No</th>
                                    <th  width="10%">Area</th>
                                    <th  width="10%">Sub Area</th>
                                    <th  width="15%">Item Penilaian</th>
                                    <th  width="20%">Komponen Penilaian</th>
                                    <th  width="10%">Parameter</th>
                                    <th  width="15%">Keterangan</th>
                                    <th  width="15%">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($rs_belum_dinilai->count() > 0)
                                    @foreach($rs_belum_dinilai as $index => $belum_dinilai)
                                        <tr>
                                            <td  style="text-align:center;">{{$index+1}}.</td>
                                            <td  >{{ $belum_dinilai->area }}</td>
                                            <td >{{ $belum_dinilai->sub_area }}</td>
                                            <td  >{{ $belum_dinilai->item }} #{{$belum_dinilai->unique_id}}</td>

                                            <!-- komponen first element -->
                                            @if($belum_dinilai->rs_komponen->count() > 0)
                                            <td>
                                                1. {{$belum_dinilai->rs_komponen[0]->name}}
                                            </td>
                                            <td style="text-align: center;">{{!empty($belum_dinilai->rs_komponen[0]->parameter) ? $belum_dinilai->rs_komponen[0]->parameter : '-' }}</td>
                                            <td style="text-align: center;">{{!empty($belum_dinilai->rs_komponen[0]->description) ? $belum_dinilai->rs_komponen[0]->description : '-'}}</td>
                                            <td style="text-align: center;">
                                                -
                                            </td>
                                            @else
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            @endif
                                        </tr>

                                        <!-- loop komponen -->
                                        @foreach($belum_dinilai->rs_komponen as $index2 => $komponen)
                                            <!-- skip first element -->
                                            @if ($loop->first)
                                                @continue
                                            @endif

                                            <tr>
                                                <td  ></td>
                                                <td ></td>
                                                <td ></td>
                                                <td ></td>
                                                <td >
                                                    {{$index2+1}}. {{$komponen->name}}
                                                </td>
                                                @if(!empty($komponen->parameter))
                                                    <td >
                                                        {{$komponen->parameter}}
                                                    </td>
                                                @else
                                                    <td style="text-align: center;">
                                                        -
                                                    </td>
                                                @endif

                                                @if(!empty($komponen->description))
                                                    <td >
                                                        {{$komponen->description}}
                                                    </td>
                                                @else
                                                    <td style="text-align: center;">
                                                        -
                                                    </td>
                                                @endif
                                                <td style="text-align: center;">
                                                    -
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" style="text-align: center;">Tidak ada data.</td>
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </li>
                    @endif
                </ol>
                <br>
            </div>
        </div>
  </div>
</body>

</html>