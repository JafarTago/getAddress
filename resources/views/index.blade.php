<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
    </style>

    <style>
        body {
            font-family: 'Nunito';
        }
    </style>
</head>
<body class="antialiased">
<form action="" method="get">
    <input type="text" name="address" value="{{$address}}" size="50">
    <button>查詢</button>
    <iframe width="100%" height="600"
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDoNPWp-LVfOKpfclro_qR0FhGUDTJEDig&q={{ $address }}"
            allowfullscreen>
    </iframe>
    <pre>{{ $jsonFormat }}</pre>

</form>
</body>
</html>
{{--https://www.google.com/maps/embed/v1/geocode?key=AIzaSyDoNPWp-LVfOKpfclro_qR0FhGUDTJEDig&address=台灣台北市萬華區康定路190號--}}
{{--https://maps.googleapis.com/maps/api/geocode/json?address=台灣台北市萬華區康定路190號--}}
{{--https://maps.googleapis.com/maps/api/geocode/json?address=台灣台北市萬華區康定路190號&key=AIzaSyDoNPWp-LVfOKpfclro_qR0FhGUDTJEDig--}}
