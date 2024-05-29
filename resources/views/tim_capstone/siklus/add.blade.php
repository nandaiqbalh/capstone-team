@extends('tim_capstone.base.app')

@section('title')
    Siklus
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Siklus</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Siklus</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/tim-capstone/siklus') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <form action="{{ url('/tim-capstone/siklus/add-process') }}" method="post" autocomplete="off">
                <div class="card-body">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama Siklus<span class="text-danger">*</span></label>
                                <select class="form-select" name="nama_siklus" required>
                                    <option value="" disabled selected>Pilih Nama Siklus</option>
                                    @foreach ($siklusOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Kode Siklus<span class="text-danger">*</span></label>
                                <input placeholder="Contoh: S2T24" type="text" class="form-control" name="kode_siklus"
                                    id="kode_siklus_input" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak aktif">Tidak
                                        Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Pendaftaran Mulai<span class="text-danger">*</span></label>
                                <input style="background-color: transparent;" placeholder="Atur waktu"
                                    id="pendaftaran_mulai" type="text" class="form-control" name="pendaftaran_mulai"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Pendaftaran Selesai<span class="text-danger">*</span></label>
                                <input style="background-color: transparent;" placeholder="Atur waktu"
                                    id="pendaftaran_selesai" type="text" class="form-control" name="pendaftaran_selesai"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Batas Submit C100<span class="text-danger">*</span></label>
                                <input style="background-color: transparent;" placeholder="Atur waktu"
                                    id="batas_submit_c100" type="text" class="form-control" name="batas_submit_c100"
                                    required>
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
            flatpickr('#pendaftaran_mulai, #pendaftaran_selesai, #batas_submit_c100', {
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
