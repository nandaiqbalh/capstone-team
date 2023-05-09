@extends('admin.base.app')
@inject('dtid', 'App\Helpers\DateIndonesia')

@section('title')
    Pekerjaan Ronde
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ronde</span></h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Belum ada Ronde Pekerjaan
            </h5>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form class="form-inline" action="{{ url('/admin/checker/ronde/pekerjaan/search') }}" method="get"
                            autocomplete="off">
                            <div class="row">
                                <div class="col-auto mt-1">
                                    <input class="form-control mr-sm-2" type="search" name="search"
                                        value="{{ !empty($search) ? $search : '' }}" placeholder="Cari ..." minlength="1"
                                        required>
                                </div>
                                <div class="col-auto mt-1">
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="search">
                                        <i class="bx bx-search-alt-2"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary ml-1" type="submit" name="action"
                                        value="reset">
                                        <i class="bx bx-reset"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Lokasi</th>
                                <th>Area/Sub Area</th>
                                <th>Item Penilaian</th>
                                <th>Komponen Penilaian</th>
                                <th>Pekerjaan</th>
                                <th>Status Pengerjaan</th>
                                <th>Revisi</th>
                                <th width="18%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td class="text-center" colspan="9">Tidak ada data.</td>
                            </tr>
                         
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->

                {{-- tombol submit ronde --}}

            </div>
        </div>
    </div>

@endsection
