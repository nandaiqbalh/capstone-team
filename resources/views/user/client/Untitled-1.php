<!-- inject helper date indonesia -->@inject('dtid','App\Helpers\DateIndonesia')
<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        td,
        th {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        th {
            text-align: center;
        }

        th,
        td {
            padding: 15px;
        }
    </style>
</head>

<body>
    <div>
        <table style="border: none; ">
            <tbody>
                <tr>
                    <td style="border: none;" width="50%"> <img src="{{ public_path('img/logo-pmc-text.png') }}" alt="logo" style="width: 50%;"> </td>
                    <td style="text-align: right; border: none;"> <b style="font-size: 14px;">Laporan Tagihan RFID</b> <br> <span style="font-size: 12px;"> Periode Tagihan {{$rs_month[$tagihan->bulan]}} {{$tagihan->tahun}} <br> Diunduh pada : {{$dtid->get_full_date($download_date)}} </span> </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table style="margin-top: 20px;">
            <tbody>
                <tr style="background-color: #E7E9EB;">
                    <td width="20%" style="text-align: center; font-size:11px;"> NO TAGIHAN </td>
                    <td width="20%" style="text-align: center; font-size:11px;"> PERIODE TAGIHAN </td>
                    <td width="20%" style="text-align: center; font-size:11px;"> JUMLAH RFID TERPAKAI </td>
                    <td width="20%" style="text-align: center; font-size:11px;"> BIAYA PER RFID </td>
                    <td width="20%" style="text-align: center; font-size:11px;"> SUB TOTAL </td>
                </tr>
                <tr>
                    <td style="font-size:11px; text-align: center;"> {{$tagihan->no}} </td>
                    <td style="font-size:11px; text-align: center;"> {{$rs_month[$tagihan->bulan]}} {{$tagihan->tahun}} </td>
                    <td style="text-align: right; font-size:11px;"> {{$tagihan->jumlah_rfid_terpakai}} </td>
                    <td style="text-align: right; font-size:11px;"> Rp. {{ number_format($tagihan->biaya_satuan_rfid,0,",",".") }} </td>
                    <td style="text-align: right; font-size:11px;"> Rp. {{ number_format($tagihan->sub_total_biaya,0,",",".") }} </td>
                </tr> <!-- jika rfid bulanan dibawah nilai minimum --> @if($tagihan->jumlah_rfid_terpakai < $tagihan->jumlah_minimum_rfid_terpakai) <tr>
                        <td colspan="3" style="border-bottom: 1px solid white; border-left: 1px solid white;"> </td>
                        <td style="text-align: right; font-size:11px;"> BIAYA MINIMUM </td>
                        <td style="text-align: right; font-size:11px;"> Rp. {{ number_format($tagihan->biaya_minimum,0,",",".") }} </td>
                    </tr> @endif <tr>
                        <td colspan="3" style="border-bottom: 1px solid white; border-left: 1px solid white;"> </td>
                        <td style="text-align: right; font-size:11px;"> PPN {{$parameter_tagihan->presentase_pajak_ppn}} % </td>
                        <td style="text-align: right; font-size:11px;"> Rp. {{ number_format($tagihan->biaya_pajak_ppn,0,",",".") }} </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-bottom: 1px solid white; border-left: 1px solid white;"> </td>
                        <td style="text-align: right; font-size:11px;"> <strong>TOTAL</strong> </td>
                        <td style="text-align: right; font-size:11px;"> <strong> Rp. {{ number_format($tagihan->total_biaya,0,",",".") }} </strong> </td>
                    </tr>
            </tbody>
        </table> <br> <br>
        <div style="padding-left: 50px; font-size: 11px;">
            <p><strong>Catatan :</strong></p> <!-- jika rfid bulanan dibawah nilai minimum --> @if($tagihan->jumlah_rfid_terpakai < $tagihan->jumlah_minimum_rfid_terpakai) <ul>
                    <li>Jumlah tag RFID terpakai dibulan ini ({{$tagihan->jumlah_rfid_terpakai}}) dibawah jumlah RFID minimum yang ditetapkan yaitu {{$tagihan->jumlah_minimum_rfid_terpakai}} tag RFID. </li>
                    <li>Diberlakukan biaya minimum yaitu jumlah RFID minimum x biaya per RFID.</li>
                </ul> @endif
        </div>
    </div>
</body>

</html>