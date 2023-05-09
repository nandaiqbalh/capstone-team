@inject('dtid','App\Helpers\DateIndonesia')
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Executive Report Checklist Pengawasan Program ABRT-RL {{$laporan_ronde->branch_name}} {{$laporan_ronde->round_name}} Bulan {{$dtid->get_month_year($laporan_ronde->created_date)}}</title>
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
                        Executive Report Checklist Pengawasan Program ABRT-RL
                        <br>
                        {{$laporan_ronde->branch_name}}
                        <br>
                        {{$laporan_ronde->round_name}} Bulan {{$dtid->get_month_year($laporan_ronde->created_date)}}
                    </h3>
                </div>

                <div>
                    <h3>Rekapitulasi Penilaian Berdasarkan Area </h3>
                    <table>
                        <thead  style="text-align: center;">
                            <tr>
                                <th width="5%" rowspan="2">No</th>
                                <th width="30%" rowspan="2">Area</th>
                                <th width="5%" rowspan="2">Jumlah Komponen</th>
                                <th width="15%" colspan="2">Pembersihan</th>
                                <th width="15%" colspan="2">Perbaikan</th>
                                <th width="15%" colspan="2">Penggantian</th>
                                <th width="15%" colspan="2">Belum Dinilai</th>
                            </tr>
                            <tr>
                                <th  width="7.5%">Jumlah</th>
                                <th  width="7.5%">Presentase</th>
                                <th  width="7.5%">Jumlah</th>
                                <th  width="7.5%">Presentase</th>
                                <th  width="7.5%">Jumlah</th>
                                <th  width="7.5%">Presentase</th>
                                <th  width="7.5%">Jumlah</th>
                                <th  width="7.5%">Presentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($rs_abc_area->count() > 0)
                                @foreach($rs_abc_area as $index => $abc_area)
                                <tr >
                                    <td style="text-align: center;">{{$index+1}}</td>
                                    <td>{{$abc_area->name}}</td>
                                    <td style="text-align: center;">{{$abc_area->jumlah_komponen}}</td>
                                    <td style="text-align: center;">{{$abc_area->jumlah_pembersihan}}</td>
                                    <td style="text-align: center;">{{round($abc_area->persen_pembersihan,2)}}%</td>
                                    <td style="text-align: center;">{{$abc_area->jumlah_perbaikan}}</td>
                                    <td style="text-align: center;">{{round($abc_area->persen_perbaikan,2)}}%</td>
                                    <td style="text-align: center;">{{$abc_area->jumlah_penggantian}}</td>
                                    <td style="text-align: center;">{{round($abc_area->persen_penggantian,2)}}%</td>
                                    <td style="text-align: center;">{{$abc_area->jumlah_belum_dinilai}}</td>
                                    <td style="text-align: center;">{{round($abc_area->persen_belum_dinilai,2)}}%</td>
                                </tr>
                                @endforeach
                                
                            @else
                            <tr style="text-align: center;">
                                <td colspan="9" style="text-align: center;">Tidak ada data.</td>
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
                                <td width="55%" style="border: none;">
                                    <p style="margin-top: 0; margin-bottom:2px;">Keterangan :</p>
                                    <ol style="padding-left: 17px;">
                                        <li>% Pembersihan = Jumlah komponen pembersihan / total komponen penilaian x 100%</li>
                                        <li>% Perbaikan = Jumlah komponen perbaikan / total komponen penilaian x 100%</li>
                                        <li>% Penggantian = Jumlah komponen penggantian / total komponen penilaian x 100%</li>
                                        <li>% Belum Dinilai = Jumlah komponen belum dinilai / total komponen penilaian x 100%</li>
                                    </ol>
                
                                </td>

                                <td width="30%" style="border: none;">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Bobot Penilaian Pembersihan = 1</td>
                                                <td>{{round($bobot_penilaian['persen_pembersihan'],2)}}%</td>
                                            </tr>
                                            <tr>
                                                <td>Bobot Penilaian Perbaikan = 0.5</td>
                                                <td>{{round($bobot_penilaian['persen_perbaikan'],2)}}%</td>
                                            </tr>
                                            <tr>
                                                <td>Bobot Penilaian Penggantian = 0.25</td>
                                                <td>{{round($bobot_penilaian['persen_penggantian'],2)}}%</td>
                                            </tr>
                                            <tr>
                                                <td>Bobot Penilaian Belum Dinilai = 0</td>
                                                <td>{{round($bobot_penilaian['persen_belum_dinilai'],2)}}%</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </td>

                                <td width="15%" style="border: none;vertical-align:top;">
                                    <table style="background-color: #fefe00; text-align: center;">
                                        <tbody>
                                            <tr>
                                                <td>Bobot Penilaian ABRT-RL</td>
                                            </tr>
                                            <tr>
                                                <td>{{round($bobot_penilaian['persen_abrt_rl'],2)}}%</td>
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
                                                <td>Jumlah<br> Pembersihan</td>
                                                <td>% Pembersihan</td>
                                                <td>Jumlah<br> Perbaikan</td>
                                                <td>% Perbaikan</td>
                                                <td>Jumlah<br> Penggantian</td>
                                                <td>% Penggantian</td>
                                                <td>Jumlah<br> Belum Dinilai</td>
                                                <td>% Belum Dinilai</td>
                                            </tr>
                                            <tr>
                                                <td>{{$summary['total_komponen']}}</td>
                                                <td>{{$summary['total_pembersihan']}}</td>
                                                <td>{{round($summary['persen_pembersihan'],2)}}%</td>
                                                <td>{{$summary['total_perbaikan']}}</td>
                                                <td>{{round($summary['persen_perbaikan'],2)}}%</td>
                                                <td>{{$summary['total_penggantian']}}</td>
                                                <td>{{round($summary['persen_penggantian'],2)}}%</td>
                                                <td>{{$summary['total_belum_dinilai']}}</td>
                                                <td>{{round($summary['persen_belum_dinilai'],2)}}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <br>
                <div class="akumulasi-parameter">
                    <table>
                        <tbody>
                            <tr>
                                <td width="75%" style="border: none;">
                                    <table style="text-align: center;">
                                        <tbody>
                                            <tr style="vertical-align: middle;">
                                                <td width="20%">Aman</td>
                                                <td width="20%">Bersih</td>
                                                <td width="20%">Rapih</td>
                                                <td width="20%">Tampak Baru</td>
                                                <td width="20%">Ramah Lingkungan</td>
                                            </tr>
                                            <tr>
                                                <td>{{round($akumulasi_parameter['persen_aman'],2)}}%</td>
                                                <td>{{round($akumulasi_parameter['persen_bersih'],2)}}%</td>
                                                <td>{{round($akumulasi_parameter['persen_rapih'],2)}}%</td>
                                                <td>{{round($akumulasi_parameter['persen_tampak_baru'],2)}}%</td>
                                                <td>{{round($akumulasi_parameter['persen_ramah_lingkungan'],2)}}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>

                                <td width="25%" style="border: none;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <br>
                <div class="signature">
                    <div class="first-section">
                        <table>
                            <tbody>
                                <tr style="text-align: left;">
                                    <td width="33.3333333333%">
                                        <table>
                                            <tbody style="text-align: left;">
                                                <tr >
                                                    <td>Dibuat Oleh,</td>
                                                </tr>
                                                <tr>
                                                    <td>Manager Penunjang Umum,</td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 60px;">
                                                        @if(!empty($laporan_ronde->checker_approved_date))
                                                        <img src="https://jangumhermina.com/img/approved.png" alt="approved">
                                                        @elseif(!empty($laporan_ronde->checker_approved_by_system))
                                                        <img src="https://jangumhermina.com/img/approved-by-system.png" alt="approved">
                                                        @else
                
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$laporan_ronde->checker_name}}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                    <td width="33.3333333333%">
                                        <table>
                                            <tbody style="text-align: left;">
                                                <tr>
                                                    <td>Diperiksa Oleh,</td>
                                                </tr>
                                                <tr>
                                                    <td>Wakil Direktur,</td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 60px;">
                                                        @if(!empty($laporan_ronde->verifikator_1_approved_date))
                                                        <img src="https://jangumhermina.com/img/approved.png" alt="approved">
                                                        @elseif(!empty($laporan_ronde->verifikator_1_approved_by_system))
                                                        <img src="https://jangumhermina.com/img/approved-by-system.png" alt="approved">
                                                        @else
                                                        
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$laporan_ronde->verifikator_1_name}}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                    <td width="33.3333333333%">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>Disetujui Oleh,</td>
                                                </tr>
                                                <tr>
                                                    <td>Direktur,</td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 60px;">
                                                        @if(!empty($laporan_ronde->verifikator_2_approved_date))
                                                        <img src="https://jangumhermina.com/img/approved.png" alt="approved">
                                                        @elseif(!empty($laporan_ronde->verifikator_2_approved_by_system))
                                                        <img src="https://jangumhermina.com/img/approved-by-system.png" alt="approved">
                                                        @else
                                                        
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$laporan_ronde->verifikator_2_name}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <br>
                    <br>
                    <div class="second-section">
                        <table >
                            <tbody >
                                <tr>
                                    <td width="33.3333333333%">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>Diketahui Oleh,</td>
                                                </tr>
                                                <tr>
                                                    <td>Kepala Departemen Penunjang Umum,</td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 50px;">
                
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$laporan_ronde->validator_name}}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>

                                    <td width="33.3333333333%">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>Diketahui Oleh,</td>
                                                </tr>
                                                <tr>
                                                    <td>Direktur Regional,</td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 50px;">
                
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$laporan_ronde->direg_name}}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>

                                    <td width="33.3333333333%">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>Diketahui Oleh,</td>
                                                </tr>
                                                <tr>
                                                    <td>Direktur Operasional,</td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 50px;">
                
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$laporan_ronde->dirop_name}}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>      

        </div>
  </div>
</body>

</html>