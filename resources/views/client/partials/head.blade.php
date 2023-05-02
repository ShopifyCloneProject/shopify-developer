<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Foxic HTML Template - Index Page</title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.ico" />
    <!-- Vendor CSS -->
    <link href="/assets/css/vendor/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/vendor/vendor.min.css" rel="stylesheet">
    <link href="/assets/css/vendor/advance.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <!-- Custom font -->
    <link href="/assets/fonts/icomoon/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open%20Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="/css/common.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="/assets/css/core.css" />
    <link rel="stylesheet" href="{{ mix('css/common.css')}}" />
    <script src="/assets/js/vendor-special/jquery.min.js"></script>
    @include('client.partials.fbpixel')
    <script type="text/javascript">
        const API_URL = '{{ Config::get('CALL_FRONT_APP_URL') }}';
        const globalsettings = '{!! json_encode($globalSettings) !!}';
        const menudata = @json($menudata);
    </script>
    @if($head_style != '')
        {!! $head_style !!}
    @endif
</head>