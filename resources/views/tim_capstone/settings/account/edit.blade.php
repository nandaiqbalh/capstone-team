@extends('tim_capstone.base.app')

@section('title')
    Pengaturan Profil Akun
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('ijaboCropTool/ijaboCropTool.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Profil Akun</h5>
        <!-- notification -->
        @include('template.notification')

        <div class="row">
            <div class="col-sm-3">
                <div class="card  h-100">
                    <div class="card-body text-center">
                        <a href="#" class="btn-img-preview  mt-2 "
                            data-img="{{ asset($account->user_img_path . $account->user_img_name) }}" data-bs-toggle="modal"
                            data-bs-target="#modal-preview">
                            {{-- <img src="{{ asset($account->user_img_path.$account->user_img_name) }}" class="rounded-circle img-fluid" style="width: 60%;"> --}}
                            <a href="#" class="btn-img-preview  mt-2"
                                data-img="{{ asset($account->user_img_path . $account->user_img_name) }}"
                                data-bs-toggle="modal" data-bs-target="#modal-preview">
                                @if (!empty($account->user_img_name))
                                    <img src="{{ asset($account->user_img_path . $account->user_img_name) }}"
                                        class="rounded-circle img-fluid" style="width: 60%;">
                                @else
                                    <!-- Gambar default jika user_img_name kosong atau tidak ada -->
                                    <img src="{{ asset('img/default.jpg') }}" class="rounded-circle img-fluid"
                                        style="width: 60%;">
                                @endif
                            </a>
                        </a>
                        <br><br><br>
                        <input type="file" class="form-control" id="user_img" name="user_img">
                        <small class="form-text text-muted">Format jpg/png, max 5 Mb.</small>
                        <br>
                        @if (empty($account->user_img_name))
                            <label class="form-label" for="user_img">Pilih</label>
                        @else
                            <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                <label class="form-label" for="user_img">{{ $account->user_img_name }}</label>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="card h-100">
                    <h5 class="card-header">Data Profil Akun</h5>
                    <form action="{{ url('/admin/settings/account/edit_process') }}" method="post" autocomplete="off"
                        enctype="multipart/form-data">
                        <div class="card-body">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ $account->user_id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="user_name" readonly
                                            value="{{ old('user_name', $account->user_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nomor Telepon<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="no_telp"
                                            value="{{ old('no_telp', $account->no_telp) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="user_email"
                                            value="{{ old('user_email', $account->user_email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nomor Induk<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="id_pengguna"
                                            value="{{ old('id_pengguna', $account->nomor_induk) }}" minlength="6"
                                            maxlength="11" pattern="[0-9]+" required readonly disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Jenis Kelamin<span class="text-danger">*</span></label>
                                        <select class="form-select" name="jenis_kelamin" id="jenis_kelamin" required>
                                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                            <option value="laki-laki"
                                                {{ old('jenis_kelamin', $account->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="perempuan"
                                                {{ old('jenis_kelamin', $account->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer float-end">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <br>

        <!-- card-->
        <div class="card">
            <h5 class="card-header">Ganti Kata Sandi</h5>
            <form action="{{ url('/admin/settings/account/edit_password') }}" method="post" autocomplete="off">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{ $account->user_id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Kata Sandi Saat Ini<span class="text-danger">*</span></label>
                                {{-- <input type="password" class="form-control" name="current_password" required> --}}
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" name="current_password"
                                        id="current_password" placeholder="Masukkan kata sandi saat ini" required>
                                    <span class="input-group-text toggle-password" data-target="current_password">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Kata Sandi Baru<span class="text-danger">*</span></label>
                                {{-- <input type="password" class="form-control" name="new_password" required> --}}
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" name="new_password" id="new_password"
                                        placeholder="Masukkan kata sandi baru" required>
                                    <span class="input-group-text toggle-password" data-target="new_password">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Minimal 8 karakter.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Ulangi Kata Sandi Baru<span class="text-danger">*</span></label>
                                {{-- <input type="password" class="form-control" name="repeat_new_password" required> --}}
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" name="repeat_new_password"
                                        id="repeat_new_password" placeholder="Masukkan ulang kata sandi baru" required>
                                    <span class="input-group-text toggle-password" data-target="repeat_new_password">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Minimal 8 karakter.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer float-end">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('ijaboCropTool/ijaboCropTool.min.js') }}"></script>
    <script>
        $('#user_img').ijaboCropTool({
            preview: '.image-previewer',
            setRatio: 1,
            allowedExtensions: ['jpg', 'jpeg', 'png'],
            buttonsText: ['CROP', 'QUIT'],
            buttonsColor: ['#30bf7d', '#ee5155', -15],
            processUrl: '{{ route('crop') }}',
            withCSRF: ['_token', '{{ csrf_token() }}'],
            onSuccess: function(message, element, status) {
                alert(message);
                location.reload();
            },
            onError: function(message, element, status) {
                alert(message);
            }

        });
    </script>
    <script>
        // Fungsi untuk menangani toggle eye
        function toggleEye(targetId) {
            var passwordInput = document.getElementById(targetId);
            var eyeIcon = document.querySelector('[data-target="' + targetId + '"] i');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        }
        // Menangani klik pada toggle eye
        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                var targetId = this.getAttribute('data-target');
                toggleEye(targetId);
            });
        });
    </script>
    {{--  Password 8 karakter --}}
    <script>
        document.getElementById('new_password').addEventListener('input', function() {
            var passwordInput = this.value;
            if (passwordInput.length < 8) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
    <script>
        document.getElementById('repeat_new_password').addEventListener('input', function() {
            var passwordInput = this.value;
            if (passwordInput.length < 8) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
@endsection
