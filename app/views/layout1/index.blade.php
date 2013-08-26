<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">

    <meta name="description" content="{{ $metaDescription }}" />
    <meta name="keywords" content="{{ $metaKeywords }}" />

    <title>{{ $pageTitle }}</title>

    {{ Asset::styles() }}

</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<div id="container">

    @include('layout1.header')

    <div class="clr"></div>

    <div id="body">

        @yield('content')

    </div><!-- END of body -->

    <div class="clr"></div>

    @include('layout1.footer')

</div><!-- END of container -->

@include('parts.php_javascript')

{{ Asset::scripts() }}

@yield('scripts')

</body>
</html>
