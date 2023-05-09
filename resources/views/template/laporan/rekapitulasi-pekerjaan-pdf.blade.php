@inject('dtid','App\Helpers\DateIndonesia')
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="author" content="{{env('APP_NAME')}} ABAROBOTICS">
  <meta name="keywords" content="Diunduh pada {{date('d-m-Y H:i:s')}} Oleh {{Auth::user()->user_name}}">
  <title>EXECUTIVE REPORT HASIL PERBAIKAN DAN PENGGANTIAN SELURUH RUMAH SAKIT @if($region_name != '0') {{strtoupper($region_name)}} @endif</title>
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
        margin-bottom: 20px;
        page-break-after: avoid;
    }

    .content {
        padding: 2px;
        font-size: 10pt;
    }

    .block-2 {
        margin-left: 32px;
        page-break-after: always;
    }

    .block-3 {
        margin-left: 32px;
        page-break-inside : avoid;
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

    .keterangan {
        margin-top: 8px;
        width: 100%;
        page-break-inside : avoid;
    }

    .keterangan table {
        page-break-inside : avoid;
    }

    .summary {
        margin-top: 8px;
        width: 100%;
        page-break-inside : avoid;
    }

    .akumulasi-parameter {
        margin-top: 8px;
        width: 100%;
        page-break-inside : avoid;
    }

    .signature {
        margin-top: 20px;
        width: 100%;
        page-break-inside : avoid;
    }

    .signature table img {
        width: 50px;
    }

    .signature .first-section {
        margin-top: 8px;
        width: 100%;
        page-break-inside : avoid;
    }

    .signature .first-section table td, .signature .first-section table th {
        border: none;
    }

    .signature .second-section {
        margin-top: 8px;
        width: 100%;
        page-break-inside : avoid;
    }

    .signature .second-section table td, .signature .second-section table th {
        border: none;
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
            <div class="block-2">
                <div class="title">
                    <h3>
                        EXECUTIVE REPORT
                        <br>
                        HASIL PERBAIKAN DAN PENGGANTIAN
                        <br>
                        PENGAWASAN PROGRAM ABRT-RL										
                        <br>
                        SELURUH RUMAH SAKIT @if($region_name != '0') {{strtoupper($region_name)}} @endif										
                        <br>
                        @if($ronde != '0') RONDE {{$ronde}} @endif BULAN {{$bulan}} TAHUN {{$tahun}}
                    </h3>
                </div>
                <br>

                <div>
                    <table >
                        <thead style="text-align: center;">
                            <tr style="vertical-align: middle;">
                                <td rowspan="2">No</td>
                                <td rowspan="2">Rumah Sakit</td>
                                <td rowspan="2">Total Komponen<br> Penilaian</td>
                                <td colspan="2">Perbaikan</td>
                                <td colspan="2">Penggantian</td>
                                <td colspan="2">Selesai</td>
                                <td colspan="2">Belum Dikerjakan</td>
                            </tr>
                            <tr>
                                <td>Jumlah<br> Perbaikan</td>
                                <td>%<br> Perbaikan</td>
                                <td>Jumlah<br> Penggantian</td>
                                <td>%<br> Penggantian</td>
                                <td>Jumlah<br> Selesai</td>
                                <td>%<br> Selesai</td>
                                <td>Jumlah<br> Belum Dikerjakan</td>
                                <td>%<br> Belum Dikerjakan</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if($rs_laporan_pekerjaan->count() > 0)
                                @foreach($rs_laporan_pekerjaan as $index => $laporan_pekerjaan)
                                <tr >
                                    <td style="text-align: center;">{{$index+1}}.</td>
                                    <td>{{$laporan_pekerjaan->branch_name}}</td>
                                    <td style="text-align: center;">{{number_format($laporan_pekerjaan->total_komponen,0,",",".")}}</td>
                                    <td style="text-align: center;">{{number_format($laporan_pekerjaan->total_perbaikan,0,",",".")}}</td>
                                    <td style="text-align: center;">{{round($laporan_pekerjaan->persen_perbaikan,2)}}%</td>
                                    <td style="text-align: center;">{{number_format($laporan_pekerjaan->total_penggantian,0,",",".")}}</td>
                                    <td style="text-align: center;">{{round($laporan_pekerjaan->persen_penggantian,2)}}%</td>
                                    <td style="text-align: center;">{{number_format($laporan_pekerjaan->total_selesai,0,",",".")}}</td>
                                    <td style="text-align: center;">{{round($laporan_pekerjaan->persen_selesai,2)}}%</td>
                                    <td style="text-align: center;">{{number_format($laporan_pekerjaan->total_belum_dikerjakan,0,",",".")}}</td>
                                    <td style="text-align: center;">{{round($laporan_pekerjaan->persen_belum_dikerjakan,2)}}%</td>
                                </tr>
                                @endforeach
                                
                            @else
                            <tr style="text-align: center;">
                                <td colspan="12" style="text-align: center;">Tidak ada data.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <br>
            </div>
                
            <div class="block-3">
                <br>
                <div class="keterangan">
                    <table>
                        <tbody>
                            <tr>
                                <td width="50%" style="border: none;">
                                    <p style="margin-top: 0; margin-bottom:2px;">Keterangan :</p>
                                    <ol style="padding-left: 17px;">
                                        <li>% Pembersihan = Jumlah komponen pembersihan / total komponen penilaian x 100%</li>
                                        <li>% Perbaikan = Jumlah komponen perbaikan / total komponen penilaian x 100%</li>
                                        <li>% Penggantian = Jumlah komponen penggantian / total komponen penilaian x 100%</li>
                                        <li>% Belum Dinilai = Jumlah komponen belum dinilai / total komponen penilaian x 100%</li>
                                    </ol>
                
                                </td>
                                <td width="5%" style="border: none;"></td>

                                <td width="30%" style="border: none;">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Bobot Penilaian Pembersihan = 1</td>
                                                <td>{{round($bobot_penilaian->persen_pembersihan,2)}}%</td>
                                            </tr>
                                            <tr>
                                                <td>Bobot Penilaian Perbaikan = 0.5</td>
                                                <td>{{round($bobot_penilaian->persen_perbaikan,2)}}%</td>
                                            </tr>
                                            <tr>
                                                <td>Bobot Penilaian Penggantian = 0.25</td>
                                                <td>{{round($bobot_penilaian->persen_penggantian,2)}}%</td>
                                            </tr>
                                            <tr>
                                                <td>Bobot Penilaian Belum Dinilai = 0</td>
                                                <td>{{round($bobot_penilaian->persen_belum_dinilai,2)}}%</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </td>
                                <td width="15%" style="border: none;vertical-align:top;">
                                    <table style="background-color: #fefe00; text-align: center;">
                                        <tbody>
                                            <tr>
                                                <td>Rata - Rata Penilaian <br> ABRT-RL</td>
                                            </tr>
                                            <tr>
                                                <td>{{round($bobot_penilaian->persen_abrt_rl,2)}}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <br>
                <div class="summary">
                    <table>
                        <tbody>
                            <tr>
                                <td width="100%" style="border: none;">
                                    <table style="text-align: center;">
                                        <tbody>
                                            <tr style="vertical-align: middle;">
                                                <td>Total Komponen<br> Penilaian</td>
                                                <td>Jumlah<br> Perbaikan</td>
                                                <td>%<br> Perbaikan</td>
                                                <td>Jumlah<br> Penggantian</td>
                                                <td>%<br> Penggantian</td>
                                                <td>Jumlah<br> Selesai</td>
                                                <td>%<br> Selesai</td>
                                                <td>Jumlah<br> Belum Dikerjakan</td>
                                                <td>%<br> Belum Dikerjakan</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;">{{number_format($summary->total_komponen,0,",",".")}}</td>
                                                <td style="text-align: center;">{{number_format($summary->total_perbaikan,0,",",".")}}</td>
                                                <td style="text-align: center;">{{round($summary->persen_perbaikan,2)}}%</td>
                                                <td style="text-align: center;">{{number_format($summary->total_penggantian,0,",",".")}}</td>
                                                <td style="text-align: center;">{{round($summary->persen_penggantian,2)}}%</td>
                                                <td style="text-align: center;">{{number_format($summary->total_selesai,0,",",".")}}</td>
                                                <td style="text-align: center;">{{round($summary->persen_selesai,2)}}%</td>
                                                <td style="text-align: center;">{{number_format($summary->total_belum_dikerjakan,0,",",".")}}</td>
                                                <td style="text-align: center;">{{round($summary->persen_belum_dikerjakan,2)}}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>

                <div class="signature">

                    @if($region_name != '0')
                        <div class="first-section">
                            <table>
                                <tbody>
                                    <tr style="text-align: left;">
                                        <td width="12.5%"></td>
                                        <td width="25%">
                                            <table>
                                                <tbody style="text-align: left;">
                                                    <tr >
                                                        <td>Dibuat Oleh,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Staf ABRT-RL</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:white;">space</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 60px;">
                                                            @if($signature->staf_abrtrl_viewed)
                                                            <img src="https://jangumhermina.com/img/viewed.png" alt="viewed">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$signature->staf_abrtrl_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                        <td width="25%">
                                            <table>
                                                <tbody style="text-align: left;">
                                                    <tr>
                                                        <td>Diperiksa,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Manager Peningkatan Mutu</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dep. Penunjang Umum</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 60px;">
                                                            @if($signature->mpm_dep_jangum_viewed)
                                                            <img src="https://jangumhermina.com/img/viewed.png" alt="viewed">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$signature->mpm_dep_jangum_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                        <td width="25%">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Disetujui,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ka. Dep Penunjang Umum</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:white;">space</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 60px;">
                                                            @if($signature->kepdep_jangum_viewed)
                                                            <img src="https://jangumhermina.com/img/approved.png" alt="approved">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$signature->kepdep_jangum_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="12.5%"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>

                        <div class="second-section">
                            <table>
                                <tbody>
                                    <tr style="text-align: left;">
                                        <td width="25%"></td>
                                        <td width="25%">
                                            <table>
                                                <tbody style="text-align: left;">
                                                    <tr>
                                                        <td>Diketahui,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Direktur regional</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:white;">space</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 60px;">
                                                            @if($signature->direg_viewed)
                                                            <img src="https://jangumhermina.com/img/viewed.png" alt="viewed">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$signature->direg_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                        <td width="25%">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Diketahui,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Direktur Operasional & Umum</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:white;">space</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 60px;">
                                                            @if($signature->dirop_viewed)
                                                            <img src="https://jangumhermina.com/img/viewed.png" alt="viewed">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$signature->dirop_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="25%"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    @else
                        <div class="first-section">
                            <table>
                                <tbody>
                                    <tr style="text-align: left;">
                                        <td width="25%">
                                            <table>
                                                <tbody style="text-align: left;">
                                                    <tr >
                                                        <td>Dibuat Oleh,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Staf ABRT-RL</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:white;">space</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 60px;">
                                                            @if($signature->staf_abrtrl_viewed)
                                                            <img src="https://jangumhermina.com/img/viewed.png" alt="viewed">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$signature->staf_abrtrl_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                        <td width="25%">
                                            <table>
                                                <tbody style="text-align: left;">
                                                    <tr>
                                                        <td>Diperiksa,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Manager Peningkatan Mutu</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dep. Penunjang Umum</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 60px;">
                                                            @if($signature->mpm_dep_jangum_viewed)
                                                            <img src="https://jangumhermina.com/img/viewed.png" alt="viewed">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$signature->mpm_dep_jangum_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                        <td width="25%">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Disetujui,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ka. Dep Penunjang Umum</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:white;">space</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 60px;">
                                                            @if($signature->kepdep_jangum_viewed)
                                                            <img src="https://jangumhermina.com/img/approved.png" alt="approved">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$signature->kepdep_jangum_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="25%">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>Diketahui,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Direktur Operasional & Umum</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:white;">space</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="height: 60px;">
                                                            @if($signature->dirop_viewed)
                                                            <img src="https://jangumhermina.com/img/viewed.png" alt="viewed">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$signature->dirop_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>

                <div style="margin-top: 25px; ">
                    <p style="color: grey;">
                        <small><em>Diunduh pada {{date('d-m-Y H:i:s')}} Oleh {{Auth::user()->user_name}}</em></small>
                    </p>
                </div>
                <br>
            </div>      

        </div>
  </div>
</body>

</html>