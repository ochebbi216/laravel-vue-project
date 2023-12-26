<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon -->
    <link rel="icon" href="../../../public/assets/img/hotel_logo.png" >

    <link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap.min.css') }}">


</head>

<body>
    <div id="app"></div>
    @vite('resources/js/app.js')

	<script src="{{ URL::to('assets/js/jquery-3.5.1.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/popper.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/bootstrap.min.js') }}"></script>

</body>

</html>