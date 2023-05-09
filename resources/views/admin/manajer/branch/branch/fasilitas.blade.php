<!DOCTYPE html>
<html>

<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
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
        size: 33cm 21cm  portrait;
        margin: 1cm; 
    }

	</style>
	<center>
		<h3>FASILITAS RUMAH SAKIT {{strtoupper($nama_rs)}}</h3>
        <br>
	</center>
 
	<table style="width:100%; c">
		
		<tr>
			<th>No</th>
			<th>Ronde</th>
			<th>Area</th>
			<th>Sub Area</th>
			<th>Zona</th>
			<th>Item</th>
		</tr>
	
		@php $i=1 @endphp
		@foreach($fasilitas as $p)
		<tr>
			<td>{{ $i++ }}</td>
			<td>{{$p->ronde}}</td>
			<td>{{$p->nama_area}}</td>
			<td>{{$p->nama_sub_area}}</td>
			<td>{{$p->zona}}</td>
			<td>{{$p->nama_item}} #{{$p->unique_id}}</td>
		</tr>
		@endforeach
		
	</table>
 
</body>
<div style="margin-top: 25px; ">
	<p style="color: grey;">
		<small><em>Diunduh pada {{date('d-m-Y H:i:s')}} Oleh {{Auth::user()->user_name}}</em></small>
	</p>
</div>
</html>