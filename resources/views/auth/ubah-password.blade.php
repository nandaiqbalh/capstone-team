<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Ubah Password - Hermina ABRT-RL</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="author" content="Hermina">
    <meta name="description" content="Hermina ABRT-RL">
    <meta name="application-name" content="Hermina ABRT-RL">
    <meta name="generator" content="Ports Abarobotics">
    <meta name="robots" content="noindex, nofollow">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="Hermina ABRT-RL">
    <meta property="og:url" content="{{url()->current()}}">
    <meta property="og:site_name" content="Hermina ABRT-RL">
    <meta property="og:image" content="{{ asset('favicon.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('favicon.png') }}">
    <link href="{{ asset('favicon.png') }}" rel="icon">
    <link href="{{ asset('favicon.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>

    <!-- jQuery -->
    <script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
        
    <script type="text/javascript">
        document.onkeydown = function(e) {
            if(event.keyCode == 123) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
                return false;
            }
        }
    </script>
    {!! RecaptchaV3::initJs() !!}

  </head>

  <body >

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="{{url('/')}}" class="app-brand-link gap-2">
                  <img src="{{ asset('img/logo.png') }}" alt="logo" class="img-fluid mx-auto" >
                </a>
              </div>

              <h4 class="mb-2 text-center">Ubah Kata Sandi</h4>

              @include("template.notification")
              <br>

              <form  class="mb-3" action="{{ url('/ubah-password/process') }}" method="POST">
                {{ csrf_field()}}

                <input type="hidden" name="reset_token" value="{{$reset_token}}" required>

                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Kata Sandi BARU</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" name="password" class="form-control"  minlength="8" maxlength="20"  required/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password_confirmation">ULANGI Kata Sandi BARU</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" name="password_confirmation" class="form-control"  minlength="8" maxlength="20"  required/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3">
                  {!! RecaptchaV3::field('register') !!}
                </div>
                <br>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Simpan Kata Sandi</button>
                </div>
              </form>
              <br>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- script -->
    <script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- auto close alert -->
        <script>
            $(document).ready(function() {
              window.setTimeout(function() {
                    $('.alert-auto-close').fadeOut('slow');
                    $('.alert-auto-close').addClass('d-none');
                },5000);
            });

            console.log("%c Haii, this website made by Abarobotics https://abarobotics.com ", "background:#008037;color:#ffffff;font-family:Lucida console;font-size:12px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset")
        </script>
  </body>
</html>
