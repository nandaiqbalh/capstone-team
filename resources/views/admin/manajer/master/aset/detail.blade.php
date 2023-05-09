@extends('admin.base.app')

@section('title')
    Aset
@endsection

@section('content')
            <div class="container-xxl flex-grow-1 container-p-y">
                <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Data /</span> Aset</h5>
                <!-- notification -->
                @include("template.notification")

                <!-- Bordered Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Aset</h5>
                        <small class="text-muted float-end">
                            <a href="{{ url('/admin/manajer/master/aset') }}" class="btn btn-secondary btn-xs float-right"><i class="bx bx-chevron-left"></i> Kembali</a>
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td width="15%">Aset</td>
                                        <td width="5%">:</td>
                                        <td>{{$item->name}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15%">Keterangan</td>
                                        <td width="5%">:</td>
                                        <td>{{$item->description}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <br>
                        <hr>
                        <br>
                        <p>Aset {{$item->name}} memiliki Syarat dan Ketentuan :</p>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th>Syarat dan Ketentuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rs_item_component->count() > 0)
                                        @foreach($rs_item_component as $index => $item_component)
                                        <tr>
                                            <td class="text-center">{{ $index + $rs_item_component->firstItem() }}.</td>
                                            <td>{{ $item_component->name }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center">
                                            <td colspan="2">Tidak ada data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- pagination -->
                        <div class="row mt-3 justify-content-between">
                            <div class="col-auto ">
                                <p>Menampilkan {{ $rs_item_component->count() }} dari total {{ $rs_item_component->total() }} data.</p>
                            </div>
                            <div class="col-auto ">
                                {{ $rs_item_component->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection