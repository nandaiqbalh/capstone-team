        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo mt-2" style="justify-content:center;">
            <a href="{{url('/')}}" class="app-brand-link mt-4">
              <img src="{{ asset('img/logo.png') }}" alt="logo" class="img-fluid " style="max-width: 80%;">
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">UTAMA</span>
            </li>

            @foreach($rs_parent_menu_utama as $parent_menu_utama)
              
              @if(empty($rs_child_menu_utama[$parent_menu_utama->menu_id]))
              <li class="menu-item @if($url_segment == $parent_menu_utama->menu_url ) active @endif">
                <a href="{{url($parent_menu_utama->menu_url)}}" class="menu-link">
                  <i class="menu-icon tf-icons {{ $parent_menu_utama->menu_icon }}"></i>
                  <div data-i18n="{{ $parent_menu_utama->menu_name }}">
                    {{ $parent_menu_utama->menu_name }}
                  </div>
                </a>
              </li>

              @else
              <!-- memiliki sub menu -->
              <li class="menu-item @if($url_parent == $parent_menu_utama->menu_url) active open @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon tf-icons {{ $parent_menu_utama->menu_icon }}"></i>
                  <div data-i18n="{{ $parent_menu_utama->menu_name }}">{{ $parent_menu_utama->menu_name }}</div>
                </a>

                <ul class="menu-sub">
                  @foreach($rs_child_menu_utama[$parent_menu_utama->menu_id] as $child_menu_utama)
                  <li class="menu-item @if($url_segment == $child_menu_utama->menu_url) active @endif">
                    <a href="{{ url($child_menu_utama->menu_url) }}" class="menu-link">
                      <div data-i18n="{{ $child_menu_utama->menu_name }}">{{ $child_menu_utama->menu_name }}</div>
                    </a>
                  </li>
                  @endforeach
                </ul>
              </li>

              @endif

            @endforeach

            @if($role_id == '01')
              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">SISTEM</span>
              </li>

              @foreach($rs_parent_menu_system as $parent_menu_system)

                @if(empty($rs_child_menu_system[$parent_menu_system->menu_id]))
                <li class="menu-item @if($url_segment == $parent_menu_system->menu_url ) active @endif">
                  <a href="{{url($parent_menu_system->menu_url)}}" class="menu-link" @if($parent_menu_system->menu_url == 'logout') onclick="return confirm('Apakah anda ingin keluar?')" @endif>
                    <i class="menu-icon tf-icons {{ $parent_menu_system->menu_icon }}"></i>
                    <div data-i18n="{{ $parent_menu_system->menu_name }}">
                      {{ $parent_menu_system->menu_name }}
                    </div>
                  </a>
                </li>

                @else
                <!-- memiliki sub menu -->
                <li class="menu-item @if($url_parent == $parent_menu_system->menu_url) active open @endif">
                  <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons {{ $parent_menu_system->menu_icon }}"></i>
                    <div data-i18n="{{ $parent_menu_system->menu_name }}">{{ $parent_menu_system->menu_name }}</div>
                  </a>

                  <ul class="menu-sub">
                    @foreach($rs_child_menu_system[$parent_menu_system->menu_id] as $child_menu_system)
                    <li class="menu-item @if($url_segment == $child_menu_system->menu_url) active @endif">
                      <a href="{{ url($child_menu_system->menu_url) }}" class="menu-link">
                        <div data-i18n="{{ $child_menu_system->menu_name }}">{{ $child_menu_system->menu_name }}</div>
                      </a>
                    </li>
                    @endforeach
                  </ul>
                </li>

                @endif

              @endforeach
            @endif
          </ul>
        </aside>