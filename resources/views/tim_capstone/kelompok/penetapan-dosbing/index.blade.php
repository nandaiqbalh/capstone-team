@extends('tim_capstone.base.app')

@section('title')
    Penetapan Dosen Pembimbing
@endsection

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Penetapan Dosen Pembimbing</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <h5 class="card-header">Data Kelompok</h5>

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Siklus Pendaftaran</th>
                                <th>Persetujuan Dosbing 1</th>
                                <th>Persetujuan Dosbing 2</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_kelompok->count() > 0)
                                @foreach ($rs_kelompok as $index => $kelompok)
                                    <tr>
                                        <td class="text-center">{{ $index + $rs_kelompok->firstItem() }}.</td>
                                        <td>{{ $kelompok->nama_siklus }}</td>
                                        <td style="color: {{ $kelompok->status_dosbing1_color }}">
                                            {{ $kelompok->status_dosen_pembimbing_1 }}</td>
                                        <td style="color: {{ $kelompok->status_dosbing2_color }}">
                                            {{ $kelompok->status_dosen_pembimbing_2 }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('/tim-capstone/penetapan-dosbing/detail') }}/{{ $kelompok->id }}"
                                                class="btn btn-outline-primary btn-xs m-1 "> Tetapkan Dosen Pembimbing</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="6">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- pagination -->
                <div class="row mt-3 justify-content-between">
                    <div class="col-auto mr-auto">
                        <p>Menampilkan {{ $rs_kelompok->count() }} dari total {{ $rs_kelompok->total() }} data.</p>
                    </div>
                    <div class="col-auto ">
                        {{ $rs_kelompok->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
