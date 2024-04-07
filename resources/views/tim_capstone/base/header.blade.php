        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                    <i class="bx bx-menu bx-sm"></i>
                </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <!-- Search -->
                {{-- <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <div id="custom-branch-title" class="bg-light p-2 rounded-1">
                    @if (Auth::user()->branch_id == null)
                      @if ($role_id == '05' || $role_id == '06')
                        Holding Hermina
                      @elseif($role_id == '07')
                        Holding Hermina
                      @elseif($role_id == '08')
                        Holding Hermina
                      @elseif($role_id == '01')
                        Super Admin
                      @endif
                    @else
                      {{$branch_name}}
                    @endif
                  </div>

                </div>
              </div> --}}
                <!-- /Search -->

                <ul class="navbar-nav flex-row align-items-center ms-auto">

                    <!-- User -->
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                            data-bs-toggle="dropdown">
                            <div class="avatar avatar-online">
                                @if (!empty(Auth::user()->user_img_name))
                                    <img src="{{ asset(Auth::user()->user_img_path . Auth::user()->user_img_name) }}"
                                        alt class="w-px-40 h-auto rounded-circle" />
                                @else
                                    <img src="{{ asset('img/default.jpg') }}" alt
                                        class="w-px-40 h-auto rounded-circle" />
                                @endif
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ url('admin/settings/account') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar avatar-online">
                                                @if (!empty(Auth::user()->user_img_name))
                                                    <img src="{{ asset(Auth::user()->user_img_path . Auth::user()->user_img_name) }}"
                                                        alt class="w-px-40 h-auto rounded-circle" />
                                                @else
                                                    <img src="{{ asset('img/default.jpg') }}" alt
                                                        class="w-px-40 h-auto rounded-circle" />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="fw-semibold d-block">{{ Auth::user()->user_name }}</span>
                                            @if ($role_user == 'Checker')
                                                <small class="text-muted">Manajer Penunjang Umum</small>
                                            @elseif ($role_user == 'Verifikator 1')
                                                <small class="text-muted">Wakil Direktur</small>
                                            @elseif ($role_user == 'Verifikator 2')
                                                <small class="text-muted">Direktur</small>
                                            @else
                                                <small class="text-muted">{{ $role_user }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>

                            <li>

                                <a class="dropdown-item" href="#" id="logoutButton">
                                    <i class="bx bx-power-off me-2"></i>
                                    <span class="align-middle">Keluar</span>
                                </a>
                                <script>
                                    document.getElementById('logoutButton').addEventListener('click', function(event) {
                                        event.preventDefault(); // Mencegah perilaku default tautan
                                        konfirmasiLogout();
                                    });

                                    function konfirmasiLogout() {
                                        Swal.fire({
                                            title: 'Konfirmasi',
                                            text: 'Apakah Anda yakin untuk keluar dari akun Anda?',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            cancelButtonText: 'Batal',
                                            confirmButtonText: 'Ya, Keluar!'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Tindakan logout di sini
                                                window.location.href = '{{ url('/logout') }}'; // Sesuaikan dengan URL logout sesuai kebutuhan
                                            }
                                        });
                                    }
                                </script>

                            </li>
                        </ul>
                    </li>
                    <!--/ User -->
                </ul>
            </div>
        </nav>
