
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="{{ URL::to('favicon.ico') }}">

    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,600,700&display=swap" rel="stylesheet">
    
    <link href="{{ URL::to('userAssets/css/open-iconic-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/owl.theme.default.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/aos.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/jquery.timepicker.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/flaticon.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/icomoon.css') }}" rel="stylesheet">
    <link href="{{ URL::to('userAssets/css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div id="userapp"></div>


    <script src="{{ URL::to('userAssets/js/jquery.min.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/jquery-migrate-3.0.1.min.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/popper.min.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/jquery.easing.1.3.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/jquery.stellar.min.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/aos.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/jquery.animateNumber.min.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::to('userAssets/js/scrollax.min.js') }}"></script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script> -->
    <!-- <script src="{{ URL::to('userAssets/js/google-map.js') }}"></script> -->
    <script src="{{ URL::to('userAssets/js/main.js') }}"></script>
    @vite('resources/js/user.js') 

</body>
</html>