
@extends('admin.base.app')

@section('title')
    Item Penilaian
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Registrasi /</span> Item Penilaian</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Item Penilaian</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/checker/register/item-penilaian') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                         <!-- table info -->
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover">

                                <tbody>
                                    <tr>
                                        <td width="15%">Lokasi</td>
                                        <td width="5%">:</td>
                                        <td>{{ $item->nama_lokasi }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Area</td>
                                        <td width="5%">:</td>
                                        <td>{{ $item->nama_area }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Sub Area</td>
                                        <td width="5%">:</td>
                                        <td>{{ $item->nama_sub_area }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Zona</td>
                                        <td width="5%">:</td>
                                        <td>{{ $item->zona }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Item Penilaian</td>
                                        <td width="5%">:</td>
                                        <td>{{ $item->nama_item }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">ID</td>
                                        <td width="5%">:</td>
                                        <td>{{ $item->unique_id }}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Ronde</td>
                                        <td width="5%">:</td>
                                        <td>{{ $item->ronde }}</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        <h5 class="mb-0 mt-5">Detail Item Penilaian</h5>
                        <div class="table-responsive text-nowrap mt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Komponen Penilaian</th>
                                        <th>Parameter</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @if($rs_component->count() > 0)
                                            @foreach($rs_component as $index => $component)
                                            <tr>
                                                <td class="text-center">{{ $index + $rs_component->firstItem() }}.</td>
                                                <td>{{ $component->name }} </td>
                                                <td>{{ $component->parameter_true }}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center" colspan="4">Tidak ada data.</td>
                                            </tr>
                                        @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto mr-auto">
                                <p>Menampilkan {{ $rs_component->count() }} dari total {{ $rs_component->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_component->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
@endsection