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
                <h5 class="mb-0">Ubah Siklus</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/siklus') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <form id="updateForm" action="{{ url('/admin/siklus/edit-process') }}" method="post" autocomplete="off">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $siklus->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Nama Siklus<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_siklus"
                                    value="{{ $siklus->nama_siklus }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Kode Siklus<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_siklus"
                                    value="{{ $siklus->kode_siklus }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="aktif" @if (old('status', $siklus->status) == 'aktif') selected @endif>Aktif</option>
                                    <option value="tidak aktif" @if (old('status', $siklus->status) == 'tidak aktif') selected @endif>Tidak
                                        Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Pendaftaran Mulai<span class="text-danger">*</span></label>
                                <input id="pendaftaran_mulai" type="text" class="form-control" name="pendaftaran_mulai"
                                    value="{{ $siklus->pendaftaran_mulai }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Pendaftaran Selesai<span class="text-danger">*</span></label>
                                <input id="pendaftaran_selesai" type="text" class="form-control"
                                    name="pendaftaran_selesai" value="{{ $siklus->pendaftaran_selesai }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Batas Submit C100<span class="text-danger">*</span></label>
                                <input id="batas_submit_c100" type="text" class="form-control" name="batas_submit_c100"
                                    value="{{ $siklus->batas_submit_c100 }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer float-end">
                    <button type="submit" id="submitButton" class="btn btn-primary">Simpan</button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil referensi form dan tombol submit
            const form = document.getElementById('updateForm');
            const submitButton = document.getElementById('submitButton');

            // Tambahkan event listener untuk submit form
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Hentikan default submit form

                // Tampilkan SweetAlert konfirmasi
                Swal.fire({
                    title: 'Konfirmasi!',
                    text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batalkan'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lanjutkan submit form jika pengguna menekan "Ya"
                        submitButton.disabled =
                            true; // Matikan tombol submit untuk menghindari multiple submission
                        form.submit(); // Submit form
                    }
                });
            });
        });
    </script>
@endsection
