@php
    // dd(session());
@endphp

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Masuk - Capstone</title>
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
    <meta http-equiv="refresh" content="120">
    <link href="{{ asset('fav icon.png') }}" rel="icon">
    <link href="{{ asset('favicon.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/pages/page-auth.css') }}" />
  </head>

  <body >
  <div class= "section" style="background-image: linear-gradient(to top right, #84A3E9, #DFFEE6);" >
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y" >
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body" style="margin-left:10px;margin-right:10px;">
              <!-- Logo -->
              <div class="app-brand d-flex justify-content-center align-items-center" style="margin-bottom: 15px;">
                <a href="{{url('/')}}" class="app-brand-link gap-2">
                  <img style="height: 150px" src="{{ asset('img/logo.png') }}" alt="logo" class="img-fluid" >
                </a>
              </div>
              <!-- Text -->
              <h5 class="mb-2 text-center" style="color: blue;"><b>CAPSTONE TA <br> TEKNIK KOMPUTER</b></h5>
              @include("template.notification")
              <br>
              <!-- Form -->
              <form id="formAuthentication" action="{{ url('/login/process') }}" method="POST">
                {{ csrf_field()}}
                <div class="mb-2">
                  <label for="email" font-size: 16px;">Nomor Induk</label>
                  <input type="text" class="form-control" name="id_pengguna" value="{{ old('id_pengguna') }}" minlength="6" maxlength="25" placeholder="Masukkan nomor induk" required>
                </div>
                <div class="mb-2 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label for="password">Kata Sandi</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" name="password" class="form-control" minlength="8" maxlength="20" placeholder="Masukkan kata sandi" required/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  {!! RecaptchaV3::field('register') !!}
                </div>
                <br>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit" id="btn-login">Masuk</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

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
    <script type="text/javascript">
      function preventBack() {
          window.history.forward(); 
      }
        
      setTimeout("preventBack()", 0);
        
      window.onunload = function () { null };
    </script>
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

            $("#formAuthentication").on("submit", function(){
              $("#btn-login").prop('disabled', true);
            });
        });
    </script>
  </div>
  </body>
</html>
