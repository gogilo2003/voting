<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @section('title')
            PDF Document
        @show
    </title>
    <style>
        {!! file_get_contents(base_path('/resources/css/pdf.css')) !!}
    </style>
</head>

<body class="bg-white">
    @yield('content')
</body>

</html>