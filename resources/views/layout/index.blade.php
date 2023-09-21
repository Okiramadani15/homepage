<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Al-Hasyimiyah</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta http-equiv="refresh" content="3600">

    <!-- Favicons -->
    <link href="/assets/illustrations/logo-ponpes.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500&family=Inter:wght@400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    

    <!-- Vendor CSS Files -->
    <link href="{!! asset('assets/vendor/aos/aos.css') !!}" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <link href="{!! asset('assets/css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/vendor/boxicons/css/boxicons.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/vendor/glightbox/css/glightbox.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/vendor/remixicon/remixicon.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/vendor/swiper/swiper-bundle.min.css') !!}" rel="stylesheet">

    <!-- CSS File -->
    <link href="{!! asset('assets/css/style.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/css/custom.css') !!}" rel="stylesheet">
    
    <!-- fullcalendar css  -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.0.3/index.global.min.js"></script>

    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
</head>

<body>
    @php $status = ""; $titleStatus = ""; $messageStatus = ""; @endphp
    @if (session('status') == 'success')
        @php $status = "success"; $titleStatus = Session::get('title'); $messageStatus = Session::get('message'); @endphp
    @endif
    @if (session('status') == 'failed')
        @php $status = "failed"; $titleStatus = Session::get('title'); $messageStatus = Session::get('message'); @endphp
    @endif
    @if (session('status') == 'warning')
        @php $status = "warning"; $titleStatus = Session::get('title'); $messageStatus = Session::get('message'); @endphp
    @endif
    @if ($errors->any())
        @php $status = "error"; $titleStatus = Session::get('title'); $messageStatus = Session::get('message'); @endphp
    @endif
    <input id="status" type="hidden" value="{{$status}}">
    <input id="title-status" type="hidden" value="{{$titleStatus}}">
    <input id="message-status" type="hidden" value="{{$messageStatus}}">



    @include('layout.navbar')

    @yield('content')

    @include('layout.footer')

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{!! asset('assets/vendor/purecounter/purecounter_vanilla.js') !!}"></script>
  <script src="{!! asset('assets/vendor/aos/aos.js') !!}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <script src="{!! asset('assets/vendor/glightbox/js/glightbox.min.js') !!}"></script>
  <script src="{!! asset('assets/vendor/swiper/swiper-bundle.min.js') !!}"></script>
  <script src="{!! asset('assets/vendor/php-email-form/validate.js') !!}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{!! asset('assets/js/jquery-3.6.0.js') !!}"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>

  <script>
    const status = $('#status').val();
    const title = $("#title-status").val();
    const message = $('#message-status').val();

    if(status == "success"){
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            showConfirmButton: false,
        });
    }else if(status == 'failed'){
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonColor: 'darkorange',
        });
    }else if(status == 'error'){
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonColor: 'darkorange',
        });
    }else if(status == "warning"){
        Swal.fire({
            icon: 'warning',
            title: title,
            text: message,
            showConfirmButton: false,
        });
    }

  </script>

  <!-- JS File -->
  <script src="/assets/js/main.js"></script>
  
  @yield('script')

</body>

</html>