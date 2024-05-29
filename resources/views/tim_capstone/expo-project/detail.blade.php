@extends('tim_capstone.base.app')

@section('title')
    Expo
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">Expo</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Expo</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/tim-capstone/expo-project') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left"></i> Kembali</a>
                </small>
            </div>
            <div class="card-body">
                <!-- table info -->
                <div class="table-responsive">
                    <table class="table table-borderless table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="20%"></th>
                                <th width="5%"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Siklus </td>
                                <td>:</td>
                                <td>{{ $expo->nama_siklus }}</td>
                            </tr>
                            <tr>
                                <td>Tempat </td>
                                <td>:</td>
                                <td>{{ $expo->tempat }}</td>
                            </tr>
                            <tr>
                                <td>Hari, tanggal</td>
                                <td>:</td>
                                <td>{{ $expo->hari_expo }}, {{ $expo->tanggal_expo }}</td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td>:</td>
                                <td>{{ $expo->waktu_expo }} WIB</td>
                            </tr>
                            <tr>
                                <td>Batas Pendaftaran</td>
                                <td>:</td>
                                <td>{{ $expo->tanggal_selesai }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <hr>

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Nomor Kelompok</th>
                                <th>Status Kelompok</th>
                                <th>Berkas</th>
                                <th>Status Pendaftaran</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rs_kel_expo->count() > 0)
                                @foreach ($rs_kel_expo as $index => $pendaftaran)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}.</td>
                                        <td>{{ $pendaftaran->nomor_kelompok }}</td>
                                        <td style="color: {{ $pendaftaran->status_expo_color }}">
                                            {{ $pendaftaran->status_kelompok }}</td>
                                        <td><a href="{{ $pendaftaran->link_berkas_expo }}"
                                                style="text-decoration: underline; color: blue;" target="_blank">Link
                                                berkas</a></td>

                                        <td class="text-center">

                                            @if ($pendaftaran->status_expo == 'Menunggu Persetujuan Expo')
                                                {{-- <a href="{{ url('/tim-capstone/expo-project/terima') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-success btn-xs m-1 "onclick="return confirm('Apakah anda ingin menerima {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Terima</a> --}}
                                                <a href="{{ url('/tim-capstone/expo-project/terima') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-success btn-xs m-1 terimaButton" data-id="{{ $pendaftaran->id_pendaftaran }}">
                                                        Terima
                                                    </a>
                                                    
                                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                    <script>
                                                        // Membuat event listener untuk semua tombol dengan class "terimaButton"
                                                        document.querySelectorAll('.terimaButton').forEach(button => {
                                                            button.addEventListener('click', function(event) {
                                                                event.preventDefault(); // Menghentikan aksi default dari tombol href
                                                    
                                                                const idPendaftaran = this.getAttribute('data-id'); // Mendapatkan ID pendaftaran dari atribut data-id
                                                    
                                                                Swal.fire({
                                                                    title: 'Apakah Anda yakin?',
                                                                    text: `Anda akan menerima pendaftaran ${idPendaftaran}`,
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#007bff',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'Ya, terima!',
                                                                    cancelButtonText: 'Batal'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Redirect atau lakukan aksi lain di sini
                                                                        window.location.href = "{{ url('/tim-capstone/expo-project/terima') }}/" + idPendaftaran;
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>
                                                     
                                                {{-- <a href="{{ url('/tim-capstone/expo-project/tolak') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda ingin menolak {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Tolak</a> --}}
                                                <a href="{{ url('/tim-capstone/expo-project/tolak') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-danger btn-xs m-1 tolakButton" data-id="{{ $pendaftaran->id_pendaftaran }}">
                                                        Tolak
                                                    </a>
                                                    
                                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                    <script>
                                                        // Membuat event listener untuk semua tombol dengan class "tolakButton"
                                                        document.querySelectorAll('.tolakButton').forEach(button => {
                                                            button.addEventListener('click', function(event) {
                                                                event.preventDefault(); // Menghentikan aksi default dari tombol href
                                                    
                                                                const idPendaftaran = this.getAttribute('data-id'); // Mendapatkan ID pendaftaran dari atribut data-id
                                                    
                                                                Swal.fire({
                                                                    title: 'Apakah Anda yakin?',
                                                                    text: `Anda akan menolak pendaftaran ${idPendaftaran}`,
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#d33',
                                                                    cancelButtonColor: '#3085d6',
                                                                    confirmButtonText: 'Ya, tolak!',
                                                                    cancelButtonText: 'Batal'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Redirect atau lakukan aksi lain di sini
                                                                        window.location.href = "{{ url('/tim-capstone/expo-project/tolak') }}/" + idPendaftaran;
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>
                                                     
                                            @elseif($pendaftaran->status_expo == 'Kelompok Disetujui Expo')
                                                {{-- <a href="{{ url('/tim-capstone/expo-project/tolak') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-danger btn-xs m-1 "
                                                    onclick="return confirm('Apakah anda ingin menolak {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Tolak</a> --}}
                                                <a href="{{ url('/tim-capstone/expo-project/tolak') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-danger btn-xs m-1 tolakButton" data-id="{{ $pendaftaran->id_pendaftaran }}">
                                                        Tolak
                                                    </a>
                                                    
                                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                    <script>
                                                        // Membuat event listener untuk semua tombol dengan class "tolakButton"
                                                        document.querySelectorAll('.tolakButton').forEach(button => {
                                                            button.addEventListener('click', function(event) {
                                                                event.preventDefault(); // Menghentikan aksi default dari tombol href
                                                    
                                                                const idPendaftaran = this.getAttribute('data-id'); // Mendapatkan ID pendaftaran dari atribut data-id
                                                    
                                                                Swal.fire({
                                                                    title: 'Apakah Anda yakin?',
                                                                    text: `Anda akan menolak pendaftaran ${idPendaftaran}`,
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#d33',
                                                                    cancelButtonColor: '#3085d6',
                                                                    confirmButtonText: 'Ya, tolak!',
                                                                    cancelButtonText: 'Batal'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Redirect atau lakukan aksi lain di sini
                                                                        window.location.href = "{{ url('/tim-capstone/expo-project/tolak') }}/" + idPendaftaran;
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>
                                                     
                                            @elseif($pendaftaran->status_expo == 'Kelompok Tidak Disetujui Expo')
                                                {{-- <a href="{{ url('/tim-capstone/expo-project/terima') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-success btn-xs m-1 "onclick="return confirm('Apakah anda ingin menerima {{ $pendaftaran->nomor_kelompok }} ?')">
                                                    Terima</a> --}}
                                                <a href="{{ url('/tim-capstone/expo-project/terima') }}/{{ $pendaftaran->id_pendaftaran }}"
                                                    class="btn btn-outline-success btn-xs m-1 terimaButton" data-id="{{ $pendaftaran->id_pendaftaran }}">
                                                        Terima
                                                    </a>
                                                    
                                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                    <script>
                                                        // Membuat event listener untuk semua tombol dengan class "terimaButton"
                                                        document.querySelectorAll('.terimaButton').forEach(button => {
                                                            button.addEventListener('click', function(event) {
                                                                event.preventDefault(); // Menghentikan aksi default dari tombol href
                                                    
                                                                const idPendaftaran = this.getAttribute('data-id'); // Mendapatkan ID pendaftaran dari atribut data-id
                                                    
                                                                Swal.fire({
                                                                    title: 'Apakah Anda yakin?',
                                                                    text: `Anda akan menerima pendaftaran ${idPendaftaran}`,
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#007bff',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'Ya, terima!',
                                                                    cancelButtonText: 'Batal'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Redirect atau lakukan aksi lain di sini
                                                                        window.location.href = "{{ url('/tim-capstone/expo-project/terima') }}/" + idPendaftaran;
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    </script>
                                                     
                                            @else
                                                @if (
                                                    $pendaftaran->status_expo == 'Lulus Expo Project' ||
                                                        $pendaftaran->status_expo == 'Gagal Expo Project' ||
                                                        $pendaftaran->status_expo == 'Kelompok Disetujui Expo')
                                                    <span style="color: #1E90FF">Kelompok Disetujui Expo!</span>
                                                @else
                                                    <span style="color: #FF0000">Kelompok Tidak Disetujui Expo!</span>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="text-center">

                                            @if (
                                                $pendaftaran->status_expo == 'Lulus Expo Project' ||
                                                    $pendaftaran->status_expo == 'Gagal Expo Project' ||
                                                    $pendaftaran->status_expo == 'Kelompok Disetujui Expo')
                                                @if ($pendaftaran->status_kelompok == 'Lulus Expo Project')
                                                    {{-- <a href="{{ url('/tim-capstone/expo-project/to-gagal') }}/{{ $pendaftaran->id_kelompok }}"
                                                        class="btn btn-outline-danger btn-xs m-1 "
                                                        onclick="return confirm('Apakah anda yakin kelompok {{ $pendaftaran->nomor_kelompok }} tidak lulus?')">
                                                        Gagal</a> --}}
                                                    <a href="{{ url('/tim-capstone/expo-project/to-gagal') }}/{{ $pendaftaran->id_kelompok }}"
                                                        class="btn btn-outline-danger btn-xs m-1 gagalButton" data-id="{{ $pendaftaran->nomor_kelompok }}">
                                                            Gagal
                                                        </a>
                                                        
                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                        <script>
                                                            // Membuat event listener untuk semua tombol dengan class "gagalButton"
                                                            document.querySelectorAll('.gagalButton').forEach(button => {
                                                                button.addEventListener('click', function(event) {
                                                                    event.preventDefault(); // Menghentikan aksi default dari tombol href
                                                        
                                                                    const idKelompok = this.getAttribute('data-id'); // Mendapatkan ID kelompok dari atribut data-id
                                                        
                                                                    Swal.fire({
                                                                        title: 'Apakah Anda yakin?',
                                                                        text: `Anda akan menandai kelompok ${idKelompok} sebagai tidak lulus!`,
                                                                        icon: 'warning',
                                                                        showCancelButton: true,
                                                                        confirmButtonColor: '#d33',
                                                                        cancelButtonColor: '#3085d6',
                                                                        confirmButtonText: 'Ya, tandai sebagai tidak lulus!',
                                                                        cancelButtonText: 'Batal'
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                            // Redirect atau lakukan aksi lain di sini
                                                                            window.location.href = "{{ url('/tim-capstone/expo-project/to-gagal') }}/" + idKelompok;
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        </script>
                                                         
                                                @elseif($pendaftaran->status_kelompok == 'Gagal Expo Project')
                                                    {{-- <a href="{{ url('/tim-capstone/expo-project/to-lulus') }}/{{ $pendaftaran->id_kelompok }}"
                                                        class="btn btn-outline-success btn-xs m-1">Lulus</a> --}}
                                                    <a href="{{ url('/tim-capstone/expo-project/to-lulus') }}/{{ $pendaftaran->id_kelompok }}"
                                                        class="btn btn-outline-success btn-xs m-1 lulusButton" data-id="{{ $pendaftaran->id_kelompok }}">
                                                            Lulus
                                                        </a>
                                                        
                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                        <script>
                                                            // Membuat event listener untuk semua tombol dengan class "lulusButton"
                                                            document.querySelectorAll('.lulusButton').forEach(button => {
                                                                button.addEventListener('click', function(event) {
                                                                    event.preventDefault(); // Menghentikan aksi default dari tombol href
                                                        
                                                                    const idKelompok = this.getAttribute('data-id'); // Mendapatkan ID kelompok dari atribut data-id
                                                        
                                                                    Swal.fire({
                                                                        title: 'Apakah Anda yakin?',
                                                                        text: `Anda akan menandai kelompok ${idKelompok} sebagai lulus!`,
                                                                        icon: 'warning',
                                                                        showCancelButton: true,
                                                                        confirmButtonColor: '#28a745',
                                                                        cancelButtonColor: '#d33',
                                                                        confirmButtonText: 'Ya, tandai sebagai lulus!',
                                                                        cancelButtonText: 'Batal'
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                            // Redirect atau lakukan aksi lain di sini
                                                                            window.location.href = "{{ url('/tim-capstone/expo-project/to-lulus') }}/" + idKelompok;
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        </script>
                                                         
                                                @else
                                                    {{-- <a href="{{ url('/tim-capstone/expo-project/to-gagal') }}/{{ $pendaftaran->id_kelompok }}"
                                                        class="btn btn-outline-danger btn-xs m-1 "
                                                        onclick="return confirm('Apakah anda yakin kelompok {{ $pendaftaran->nomor_kelompok }} tidak lulus?')">
                                                        Gagal</a> --}}
                                                    <a href="{{ url('/tim-capstone/expo-project/to-gagal') }}/{{ $pendaftaran->id_kelompok }}"
                                                        class="btn btn-outline-danger btn-xs m-1 gagalButton" data-id="{{ $pendaftaran->nomor_kelompok }}">
                                                            Gagal
                                                        </a>
                                                        
                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                        <script>
                                                            // Membuat event listener untuk semua tombol dengan class "gagalButton"
                                                            document.querySelectorAll('.gagalButton').forEach(button => {
                                                                button.addEventListener('click', function(event) {
                                                                    event.preventDefault(); // Menghentikan aksi default dari tombol href
                                                        
                                                                    const idKelompok = this.getAttribute('data-id'); // Mendapatkan ID kelompok dari atribut data-id
                                                        
                                                                    Swal.fire({
                                                                        title: 'Apakah Anda yakin?',
                                                                        text: `Anda akan menandai kelompok ${idKelompok} sebagai tidak lulus!`,
                                                                        icon: 'warning',
                                                                        showCancelButton: true,
                                                                        confirmButtonColor: '#d33',
                                                                        cancelButtonColor: '#3085d6',
                                                                        confirmButtonText: 'Ya, tandai sebagai tidak lulus!',
                                                                        cancelButtonText: 'Batal'
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                            // Redirect atau lakukan aksi lain di sini
                                                                            window.location.href = "{{ url('/tim-capstone/expo-project/to-gagal') }}/" + idKelompok;
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        </script>
                                                         
                                                    {{-- <a href="{{ url('/tim-capstone/expo-project/to-lulus') }}/{{ $pendaftaran->id_kelompok }}"
                                                        class="btn btn-outline-success btn-xs m-1">Lulus</a> --}}
                                                    <a href="{{ url('/tim-capstone/expo-project/to-lulus') }}/{{ $pendaftaran->id_kelompok }}"
                                                        class="btn btn-outline-success btn-xs m-1 lulusButton" data-id="{{ $pendaftaran->id_kelompok }}">
                                                            Lulus
                                                        </a>
                                                        
                                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                        <script>
                                                            // Membuat event listener untuk semua tombol dengan class "lulusButton"
                                                            document.querySelectorAll('.lulusButton').forEach(button => {
                                                                button.addEventListener('click', function(event) {
                                                                    event.preventDefault(); // Menghentikan aksi default dari tombol href
                                                        
                                                                    const idKelompok = this.getAttribute('data-id'); // Mendapatkan ID kelompok dari atribut data-id
                                                        
                                                                    Swal.fire({
                                                                        title: 'Apakah Anda yakin?',
                                                                        text: `Anda akan menandai kelompok ${idKelompok} sebagai lulus!`,
                                                                        icon: 'warning',
                                                                        showCancelButton: true,
                                                                        confirmButtonColor: '#28a745',
                                                                        cancelButtonColor: '#d33',
                                                                        confirmButtonText: 'Ya, tandai sebagai lulus!',
                                                                        cancelButtonText: 'Batal'
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                            // Redirect atau lakukan aksi lain di sini
                                                                            window.location.href = "{{ url('/tim-capstone/expo-project/to-lulus') }}/" + idKelompok;
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        </script>
                                                         
                                                @endif
                                            @else
                                                -
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">Tidak ada data.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
