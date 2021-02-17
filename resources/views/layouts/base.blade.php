<!DOCTYPE html>
<!--
* CoreUI Free Laravel Bootstrap Admin Template
* @version v2.0.1
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gemifx</title>
    <!-- Icons-->
    <!-- Main styles for this application-->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
@yield('css')
<!-- Global site tag (gtag.js) - Google Analytics-->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
</head>
<body class="c-app">
<div id="overlay">
    <div id="progstat"></div>
    <div id="progress"></div>
</div>
<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">

    @include('layouts.sidebar')

    @include('layouts.header')

    <div class="c-body">

        <main class="c-main">

            @yield('content')

        </main>
        @include('layouts.footer')
    </div>
</div>
<script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
<script src="{{ asset('js/loader.js') }}"></script>
@yield('javascript')
</body>
</html>
