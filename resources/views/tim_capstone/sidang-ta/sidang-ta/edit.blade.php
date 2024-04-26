@extends('tim_capstone.base.app')

@section('title')
    Periode Sidang Tugas Akhir
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-6"><span class="text-muted fw-light">Admin /</span> Periode Sidang Tugas Akhir</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ubah Periode Sidang Tugas Akhir</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/sidang-ta') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <form action="{{ url('/admin/sidang-ta/edit-process') }}" method="post" autocomplete="off"
                enctype="multipart/form-data">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $periode_sidang_ta->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_periode"
                                    value="{{ $periode_sidang_ta->nama_periode }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Mulai Pendaftaran<span class="text-danger">*</span></label>
                                <input style="background-color: transparent;" id="tanggal_mulai" type="text"
                                    class="form-control" name="tanggal_mulai"
                                    value="{{ $periode_sidang_ta->tanggal_mulai }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Batas Pendaftaran<span class="text-danger">*</span></label>
                                <input style="background-color: transparent;" id="tanggal_selesai" type="text"
                                    class="form-control" name="tanggal_selesai"
                                    value="{{ $periode_sidang_ta->tanggal_selesai }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer float-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi flatpickr dengan waktu
            flatpickr('#tanggal_mulai, #tanggal_selesai', {
                dateFormat: 'Y-m-d H:i', // Format tanggal dan waktu (YYYY-MM-DD HH:mm)
                enableTime: true, // Izinkan pilihan waktu
                time_24hr: true, // Format waktu 24-jam
                minDate: new Date('2019-12-31'), // Batasi pilihan tanggal minimal ke hari ini
                maxDate: new Date('2050-12-31'), // Batasi pilihan tanggal maksimal
                defaultHour: 12, // Jam default jika tidak ada waktu terpilih
                defaultMinute: 0, // Menit default jika tidak ada waktu terpilih
                locale: {
                    buttons: {
                        now: 'Sekarang' // Mengganti teks tombol "Sekarang"
                    }
                },
                appendTo: document.body, // Append kalender ke dalam body
                inline: false, // Tidak menggunakan mode inline
                onChange: function(selectedDates, dateStr, instance) {
                    console.log('Tanggal dan waktu dipilih:', dateStr);
                }
            });
        });
    </script>
@endsection
