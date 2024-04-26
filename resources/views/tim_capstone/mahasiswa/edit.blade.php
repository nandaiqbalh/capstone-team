@extends('tim_capstone.base.app')

@section('title')
    Mahasiswa
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Mahasiswa</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ubah Mahasiswa</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/admin/mahasiswa') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <!-- Form untuk update data mahasiswa -->
                <form id="updateForm" action="{{ url('/admin/mahasiswa/edit-process') }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{ $mahasiswa->user_id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama"
                                    value="{{ $mahasiswa->user_name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>NIM<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="nim"
                                    value="{{ $mahasiswa->nomor_induk }}" required>
                            </div>
                        </div>
                    </div>

                    <br>
                    <button type="submit" class="btn btn-primary float-end" id="submitButton">Simpan</button>

                </form>

            </div>
        </div>
    </div>
    <!-- Script untuk menangani konfirmasi update -->
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
