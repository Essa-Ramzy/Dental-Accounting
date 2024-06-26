<!-- This is the main layout for the web application -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clinic Accounting</title>
    <link rel="stylesheet" href="{{ asset('resources/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/daterangepicker.min.css') }}">
    <script src="{{ asset('resources/js/jquery.min.js') }}"></script>
    <script src="{{ asset('resources/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('resources/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('resources/js/moment.min.js') }}"></script>
    <script src="{{ asset('resources/js/daterangepicker.min.js') }}"></script>
</head>

<body class="antialiased min-vh-100 d-flex flex-column overflow-x-hidden">
@yield('app_content')
</body>
</html>
