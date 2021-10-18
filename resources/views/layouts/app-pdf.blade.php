
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

        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <!-- Google Font: Lato -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

        <style>
            html {
                font-family: "Lato", sans-serif;
            }
            body {
                margin: 0;
                font-family: inherit;
                line-height: inherit;
            }

            html{font-family:Lato,sans-serif}.sabg-base{background-color:#f3f4f6!important}.sabg-primary{background-color:#023f8a!important}.sabg-secondary{background-color:#026ba8!important}.sabg-alt{background-color:#0196c6!important}#header{background:linear-gradient(130deg,hsla(0,0%,100%,.4),rgba(2,63,138,.7)),url(../images/pexels-photo-3467149.jpeg);background-size:cover}
            /*# sourceMappingURL=style.css.map*/
        </style>

        @yield('css_plugins')
        @yield('css_inline')
    </head>
    <body class="{{ $extra_class ?? '' }}">
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