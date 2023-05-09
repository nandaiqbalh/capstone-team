@inject('dtid','App\Helpers\DateIndonesia')
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Lampiran ({{$opt_judul}}) Executive Report Hasil Pekerjaan Perbaikan dan Penggantian Program ABRT-RL {{$laporan_pekerjaan->branch_name}} {{$laporan_pekerjaan->round_name}} Bulan {{$dtid->get_month_year($laporan_pekerjaan->created_date)}}</title>
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
                        Lampiran ({{$opt_judul}})
                        <br>
                        Executive Report
                        <br>
                        Hasil Pekerjaan Perbaikan dan Penggantian
                        <br>
                        Program ABRT-RL {{$laporan_pekerjaan->branch_name}}
                        <br>
                        {{$laporan_pekerjaan->round_name}} Bulan {{$dtid->get_month_year($laporan_pekerjaan->created_date)}}
                    </h3>
                </div>

                <br>
                <ol type="A" start="{{$ol_start}}">

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
                                    <th  width="15%">Foto Sebelum</th>
                                    <th  width="15%">Foto Sesudah</th>
                                    <th  width="15%">Keterangan</th>
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
                                            
                                            <td style="text-align: center;">
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$perbaikan->rs_komponen[0]->img_name_before}}/150/84" alt="foto" loading="lazy">
                                            </td>
                                            <td style="text-align: center;">
                                                @if($perbaikan->rs_komponen[0]->img_name_after)
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$perbaikan->rs_komponen[0]->img_name_after}}/150/84" alt="foto" loading="lazy">
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>{{$perbaikan->rs_komponen[0]->description}}</td>
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
                                                <td style="text-align: center;">
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name_before}}/150/84" alt="foto" loading="lazy">
                                                </td>
                                                <td>
                                                    @if($komponen->img_name_after)
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name_after}}/150/84" alt="foto" loading="lazy">
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td >
                                                    {{$komponen->description}}
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" style="text-align: center;">Tidak ada data.</td>
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
                                    <th  width="15%">Foto Sebelum</th>
                                    <th  width="15%">Foto Sesudah</th>
                                    <th  width="15%">Keterangan</th>
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
                                            <td style="text-align: center;">
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$penggantian->rs_komponen[0]->img_name_before}}/150/84" alt="foto" loading="lazy">
                                            </td>
                                            <td style="text-align: center;">
                                                @if($penggantian->rs_komponen[0]->img_name_after)
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$penggantian->rs_komponen[0]->img_name_after}}/150/84" alt="foto" loading="lazy">
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>{{$penggantian->rs_komponen[0]->description}}</td>
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
                                                <td style="text-align: center;">
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name_before}}/150/84" alt="foto" loading="lazy">
                                                </td>
                                                <td style="text-align: center;">
                                                    @if($komponen->img_name_after)
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name_after}}/150/84" alt="foto" loading="lazy">
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td >
                                                    {{$komponen->description}}
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" style="text-align: center;">Tidak ada data.</td>
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </li>
                    @endif

                    @if(in_array("Selesai", explode(",", $opt)))
                    <li style="margin-top: 20px;">
                        Selesai
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
                                    <th  width="15%">Foto Sebelum</th>
                                    <th  width="15%">Foto Sesudah</th>
                                    <th  width="15%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($rs_selesai->count() > 0)
                                    @foreach($rs_selesai as $index => $selesai)
                                        <tr>
                                            <td  style="text-align:center;">{{$index+1}}.</td>
                                            <td  >{{ $selesai->area }}</td>
                                            <td >{{ $selesai->sub_area }}</td>
                                            <td  >{{ $selesai->item }} #{{$selesai->unique_id}}</td>

                                            <!-- komponen first element -->
                                            @if($selesai->rs_komponen->count() > 0)
                                            <td>
                                                1. {{$selesai->rs_komponen[0]->name}}
                                            </td>
                                            <td style="text-align: center;">{{!empty($selesai->rs_komponen[0]->parameter) ? $selesai->rs_komponen[0]->parameter : '-' }}</td>
                                            <td style="text-align: center;">
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$selesai->rs_komponen[0]->img_name_before}}/150/84" alt="foto" loading="lazy">
                                            </td>
                                            <td style="text-align: center;">
                                                @if($selesai->rs_komponen[0]->img_name_after)
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$selesai->rs_komponen[0]->img_name_after}}/150/84" alt="foto" loading="lazy">
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>{{$selesai->rs_komponen[0]->description}}</td>
                                            @else
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            @endif
                                        </tr>

                                        <!-- loop komponen -->
                                        @foreach($selesai->rs_komponen as $index2 => $komponen)
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
                                                <td style="text-align: center;">
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name_before}}/150/84" alt="foto" loading="lazy">
                                                </td>
                                                <td style="text-align: center;">
                                                    @if($komponen->img_name_after)
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name_after}}/150/84" alt="foto" loading="lazy">
                                                    @else
                                                    -
                                                    @endif
                                                </td>

                                                @if(!empty($komponen->description))
                                                    <td >
                                                        {{$komponen->description}}
                                                    </td>
                                                @else
                                                    <td style="text-align: center;">
                                                        -
                                                    </td>
                                                @endif
                                                
                                            </tr>
                                        @endforeach

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" style="text-align: center;">Tidak ada data.</td>
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </li>
                    @endif

                    @if(in_array("Belum Dikerjakan", explode(",", $opt)))
                    <li style="margin-top: 20px;">
                        Belum Dikerjakan
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
                                    <th  width="15%">Foto Sebelum</th>
                                    <th  width="15%">Foto Sesudah</th>
                                    <th  width="15%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($rs_belum_dikerjakan->count() > 0)
                                    @foreach($rs_belum_dikerjakan as $index => $belum_dikerjakan)
                                        <tr>
                                            <td  style="text-align:center;">{{$index+1}}.</td>
                                            <td  >{{ $belum_dikerjakan->area }}</td>
                                            <td >{{ $belum_dikerjakan->sub_area }}</td>
                                            <td  >{{ $belum_dikerjakan->item }} #{{$belum_dikerjakan->unique_id}}</td>

                                            <!-- komponen first element -->
                                            @if($belum_dikerjakan->rs_komponen->count() > 0)
                                            <td>
                                                1. {{$belum_dikerjakan->rs_komponen[0]->name}}
                                            </td>
                                            <td style="text-align: center;">{{!empty($belum_dikerjakan->rs_komponen[0]->parameter) ? $belum_dikerjakan->rs_komponen[0]->parameter : '-' }}</td>
                                            <td style="text-align: center;">
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$belum_dikerjakan->rs_komponen[0]->img_name_before}}/150/84" alt="foto" loading="lazy">
                                            </td>
                                            <td style="text-align: center;">
                                                @if($belum_dikerjakan->rs_komponen[0]->img_name_after)
                                                <img style="text-align: center;" src="{{$vps_img_url}}{{$belum_dikerjakan->rs_komponen[0]->img_name_after}}/150/84" alt="foto" loading="lazy">
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td >{{$belum_dikerjakan->rs_komponen[0]->description}}</td>
                                            @else
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            <td  style="border-bottom: 1px solid white;"></td>
                                            @endif
                                        </tr>

                                        <!-- loop komponen -->
                                        @foreach($belum_dikerjakan->rs_komponen as $index2 => $komponen)
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
                                                <td style="text-align: center;">
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name_before}}/150/84" alt="foto" loading="lazy">
                                                </td>
                                                <td style="text-align: center;">
                                                    @if($komponen->img_name_after)
                                                    <img style="text-align: center;" src="{{$vps_img_url}}{{$komponen->img_name_after}}/150/84" alt="foto" loading="lazy">
                                                    @else
                                                    -
                                                    @endif
                                                </td>

                                                @if(!empty($komponen->description))
                                                    <td >
                                                        {{$komponen->description}}
                                                    </td>
                                                @else
                                                    <td style="text-align: center;">
                                                        -
                                                    </td>
                                                @endif
                                                
                                            </tr>
                                        @endforeach

                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" style="text-align: center;">Tidak ada data.</td>
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