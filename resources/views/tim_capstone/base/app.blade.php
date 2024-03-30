<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title') - Capstone </title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="author" content="Hermina">
        <meta name="description" content="Bamasama">
        <meta name="application-name" content="Bamasama">
        <meta name="generator" content="Ports Abarobotics">
        <meta name="robots" content="noindex, nofollow">
        <meta property="og:type" content="website">
        <meta property="og:title" content="@yield('title')">
        <meta property="og:description" content="Bamasama">
        <meta property="og:url" content="{{url()->current()}}">
        <meta property="og:site_name" content="Bamasama">
        <meta property="og:image" content="{{ asset('favicon.png') }}">
        <meta property="og:image:secure_url" content="{{ asset('favicon.png') }}">
        <link href="{{ asset('favicon.png') }}" rel="icon">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
        <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}" />
        <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <script src="{{ asset('vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('js/config.js') }}"></script>
        <link href="{{ asset('vendor/libs/select2-410/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('vendor/libs/select2-bootstrap-5-theme-1.3.0/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

        <!-- SweetAlert CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <!-- SweetAlert JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

        <!-- hapus broadcast, dosen -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- jQuery and Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js" integrity="sha384-wiBToD0TGpWhA05BcRPrlA7ggzoY5KwJhUpJsjQfBRCD5vTQ6j+MFlMj90eocFJc" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+Wy6nq4Z9gFgp5IuakXm2E+AnDybp4QDg8Q" crossorigin="anonymous"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- trix editor -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>

        <!-- Load CKEditor script -->
        {{-- <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script> --}}

        <!-- Load Summernote stylesheet -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

        {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>



        <!-- jQuery -->
        <script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('vendor/libs/select2-410/select2.min.js') }}"></script>

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

        <style>
            .table thead {
                background-color: rgba(105, 255, 180, 0.16);
            }

            @media only screen and (min-width: 750px) {
                #custom-branch-title {
                    min-width: 200px;
                }

            }
        </style>
    </head>

    <body >
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                @include('tim_capstone.base.sidebar')

                <div class="layout-page">
                    @include('tim_capstone.base.header')

                    <div class="content-wrapper">
                        @yield('content')

                        @include("tim_capstone.base.footer")

                        <div class="content-backdrop fade"></div>
                    </div>

                </div>

            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>

        </div>

        <!-- script -->
        <script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('vendor/js/menu.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        <script async defer src="https://buttons.github.io/buttons.js"></script>

        <script>

            $(document).ready(function(){
                // auto close alert
                // window.setTimeout(function() {
                //     $('.alert-auto-close').fadeOut('slow');
                // },5000);
                window.setTimeout(function() {
                      $('.alert-auto-close').fadeOut('slow');
                      $('.alert-auto-close').addClass('d-none');
                  },5000);

                // select2
                $(".select-2").select2({
                    theme: "bootstrap-5",
                });
                // select 2 min input
                $(".select-2-min-input").select2({
                    theme: "bootstrap-5",
                    minimumInputLength:3
                });

                // Small using Bootstrap 5 classes
                $(".select-2-modal").select2({
                    theme: "bootstrap-5",
                    dropdownParent: $(".select-2-modal").parent(), // Required for dropdown styling
                });

                // show name file name
                $('.custom-file-input').on('change',function(){
                    //get the file name
                    var fileName = $(this).val();
                    // remove fakepath
                    var fileName = fileName.replace("C:\\fakepath\\", "");
                    //replace the "Choose a file" label
                    $(this).next('.custom-file-label').html(fileName);
                });

                // img preview
                $(document).on('click', '.btn-img-preview', function() {
                    var imgSrc = $(this).data('img');
                    $('#img-preview').attr('src', imgSrc);
                });

                // youtube preview
                $(document).on('click', '.btn-yt-preview', function() {
                    var ytSrc = $(this).data('yt');
                    document.getElementById("yt-iframe").src =ytSrc;
                });

                // pdf preview
                $(document).on('click', '.btn-pdf-preview', function() {
                    var pdfSrc = $(this).data('pdf');
                    $('#pdf-preview').attr('src', pdfSrc);
                });
            });

            // console.log("%c Haii, this website made by Abarobotics https://abarobotics.com ", "background:#008037;color:#ffffff;font-family:Lucida console;font-size:12px;letter-spacing:-1px;display:block;padding:5px;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.4) inset, 0 5px 3px -5px rgba(0, 0, 0, 0.5), 0 -13px 5px -10px rgba(255,255,255,0.4) inset")
        </script>
    </body>
</html>

<!-- modal preview img -->
<div class="modal fade text-left modal-borderless" id="modal-preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <img src="" alt="img_preview" id="img-preview" class="img-fluid">
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<!-- modal preview youtube -->
<div class="modal fade text-left modal-borderless" id="yt-preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <iframe class="card-video " src="" id="yt-iframe" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<!-- modal preview pdf -->
<div class="modal fade text-left modal-borderless" id="modal-preview-pdf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <iframe src="" id="pdf-preview" width="100%" height="800px"></iframe>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
