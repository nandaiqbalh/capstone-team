@extends('tim_capstone.base.app')

@section('title')
    Expo Project
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Expo Project</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ubah Expo Project</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/expo-project') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <form action="{{ url('/admin/expo-project/edit-process') }}" method="post" autocomplete="off">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $expo->id }}">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Pilih Siklus <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_siklus" required>
                                    <option value="" disabled selected>-- Pilih --
                                    </option>
                                    @foreach ($rs_siklus as $siklus)
                                        <option value="{{ $siklus->id }}"
                                            @if ($siklus->id == $expo->id_siklus) selected @endif>
                                            {{ $siklus->nama_siklus }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tempat Expo<span class="text-danger">*</span></label>
                                <input value="{{ $expo->tempat }}" type="text" class="form-control"
                                    placeholder="Masukkan Tempat Expo" name="tempat" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Waktu<span class="text-danger">*</span></label>
                                <input value="{{ $expo->waktu }}" placeholder="Atur waktu" id="waktu" type="text"
                                    class="form-control" name="waktu" required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Pendaftaran Mulai<span class="text-danger">*</span></label>
                                <input value="{{ $expo->tanggal_mulai }}" placeholder="Atur waktu" id="tanggal_mulai"
                                    type="text" class="form-control" name="tanggal_mulai" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Pendaftaran Selesai<span class="text-danger">*</span></label>
                                <input value="{{ $expo->tanggal_selesai }}" placeholder="Atur waktu" id="tanggal_selesai"
                                    type="text" class="form-control" name="tanggal_selesai" required>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer float-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi flatpickr dengan waktu
            flatpickr('#tanggal_mulai, #tanggal_selesai, #waktu', {
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
