<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    @if(isset($data))
        @if(isset($data['productdetail']))
            @if(isset($data['productdetail']->seo_title))
                @if($data['productdetail']->seo_title == "")
                    <meta name="title" content="{{$data['productdetail']->title}}">
                    <meta name="description" content="{{strip_tags($data['productdetail']->description)}}">
                @else
                    <meta name="title" content="{{$data['productdetail']->seo_title}}">
                    <meta name="description" content="{{strip_tags($data['productdetail']->seo_description)}}">
                @endif
            @endif
        @endif
    @endif
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $icontitledata['front_title'] }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $icontitledata['front_icon'] }}" />
    <!-- Vendor CSS -->
    <link href="/theme/default/css/vendor/bootstrap.min.css" rel="stylesheet">
    <link href="/theme/default/css/vendor/vendor.min.css" rel="stylesheet">
    <link href="/theme/default/css/vendor/advance.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/theme/default/css/style.css" rel="stylesheet">
    <!-- Custom font -->
    <link href="/theme/default/fonts/icomoon/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open%20Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="/theme/default/css/common.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="/theme/default/css/core.css" />
    <link href="/theme/default/css/style-form-control.css" rel="stylesheet">
    <link href="/theme/default/css/style-scrollbar.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/common.css')}}" />

    <script src="/theme/default/js/vendor-special/jquery.min.js"></script>
    @include('partials.fbpixel')
    <script type="text/javascript">
        const API_URL = '{{ Config::get('CALL_FRONT_APP_URL') }}';
        const CLIENT_ID = '{{ env('CLIENT_ID') }}';
        const globalsettings = '{!! json_encode($globalSettings) !!}';
        const menudata = @json($menudata);
        const objThemeSettings = '{!! json_encode($objThemeSettings) !!}';
        const objTiming = '{!! json_encode($objTiming) !!}';
    </script>
    @if($head_style != '')
        {!! $head_style !!}
    @endif
</head>