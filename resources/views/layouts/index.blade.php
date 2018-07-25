<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>

    <title>MEA</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/css/application.css">
    <link rel="stylesheet" href="/css/theme/material-dashboard.min.css">

    <!--   Core JS Files   -->
    <script src="/js/theme/jquery.min.js" type="text/javascript"></script>
    <script src="/js/theme/popper.min.js" type="text/javascript"></script>
    <script src="/js/theme/bootstrap-material-design.min.js" type="text/javascript"></script>

    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="/js/theme/material-dashboard.min.js?v=2.1.0" type="text/javascript"></script>

    <!-- Application -->
    <script src="/js/application.js" type="text/javascript"></script>

</head>
<body>

@yield('content')

</body>
</html>
