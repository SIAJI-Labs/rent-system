
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ (isset($wsecond_title) && !empty($wsecond_title) ? $wsecond_title.' - ' : '').($wtitle ?? env('APP_NAME')) }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @if(!empty($wfavicon))
        <link rel="shortcut icon" href="{{ asset('assets/images/logo'.'/'.$wfavicon) }}">
        @endif

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ mix('assets/plugins/fontawesome-free/css/all.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <!-- Google Font: Lato -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
        <!-- Tailwind -->
        <link href="{{ mix('assets/css/tailwind.css') }}" rel="stylesheet">
        <link href="{{ mix('assets/css/style.css') }}" rel="stylesheet">
    
        @yield('css_plugins')
        @yield('css_inline')
    </head>
    <body class="{{ $extra_class ?? '' }} tw__bg-gray-100 tw__h-screen">
        <div class="wrapper {{ $wrapper_extra_class ?? '' }}">
            <div class="container {{ $container_extra_class ?? '' }}">
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </div>

        @yield('content_modal')

        @yield('js_plugins')
        @yield('js_inline')
    </body>
</html>