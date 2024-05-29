@extends('tim_capstone.base.app')

@section('title')
    Akun User
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Akun User</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ubah Akun User</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/tim-capstone/pengaturan/accounts') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <form action="{{ url('/tim-capstone/pengaturan/accounts/edit_process') }}" method="post" autocomplete="off">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{ $account->user_id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="user_name"
                                    value="{{ old('user_name', $account->user_name) }}" placeholder="Masukkan Nama"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nomor Induk<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nomor_induk"
                                    value="{{ old('nomor_induk', $account->nomor_induk) }}" minlength="6" maxlength="30"
                                    pattern="[0-9]+" placeholder="Masukkan Nomor Induk" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Jenis Kelamin<span class="text-danger">*</span></label>
                                <select class="form-select" name="jenis_kelamin" required>
                                    <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki"
                                        {{ old('jenis_kelamin', $account->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        {{ old('jenis_kelamin', $account->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Angkatan<span class="text-danger"></span></label>
                                <input type="text" class="form-control" name="angkatan"
                                    value="{{ old('angkatan', $account->angkatan) }}"
                                    placeholder="Masukkan Angkatan (untuk mahasiswa)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Aktifkan Akun? <span class="text-danger">*</span></label>
                                <select class="form-select" name="user_active" required>
                                    <option value="1" @if (old('user_active', $account->user_active) == '1') selected @endif>Ya</option>
                                    <option value="0" @if (old('user_active', $account->user_active) == '0') selected @endif>Tidak</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Role User <span class="text-danger">*</span></label>
                                <select class="form-select" id="role_id" name="role_id" required>
                                    <option value="" selected disabled>Pilih</option>
                                    @foreach ($rs_role as $role)
                                        <option value="{{ $role->role_id }}"
                                            @if (old('role_id', $account->role_id) == $role->role_id) selected @endif>{{ $role->role_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Email<span class="text-danger"></span></label>
                                <input type="email" class="form-control" name="user_email"
                                    value="{{ old('user_email', $account->user_email) }}" placeholder="Masukkan Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nomor Telepon</label>
                                <input type="number" class="form-control" name="no_telp"
                                    value="{{ old('no_telp', $account->no_telp) }}" placeholder="Masukkan Nomor Telepon">
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="card-footer float-end">
                    <button type="submit" class="btn btn-primary ">Simpan</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        $('#role_id').on('change', function() {
            var roleId = $('#role_id').val();

            // checker & verifikator
            if (roleId == '02' || roleId == '03' || roleId == '04') {
                $('#col-branch-id').removeClass('d-none');
                $('#branch_id').prop('required', true);
            } else {
                $('#col-branch-id').addClass('d-none');
                $('#branch_id').prop('required', false);
            }

        });

        // default dr db
        var roleId = '{{ $account->role_id }}';

        // checker & verifikator
        if (roleId == '02' || roleId == '03' || roleId == '04') {
            $('#col-branch-id').removeClass('d-none');
            $('#branch_id').prop('required', true);
        } else {
            $('#col-branch-id').addClass('d-none');
            $('#branch_id').prop('required', false);
        }

        // holding regional
        if (roleId == '08') {
            $('#col-region-id').removeClass('d-none');
            $('#region_id').prop('required', true);
        } else {
            $('#col-region-id').addClass('d-none');
            $('#region_id').prop('required', false);
        }
    </script>
@endsection
