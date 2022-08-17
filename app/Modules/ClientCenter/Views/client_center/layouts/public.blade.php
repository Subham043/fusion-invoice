<!DOCTYPE html>
<html class="public-layout">
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" media="print" href="{{ asset('assets/plugins/chosen/print.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ config('fi.headerTitleText') }}</title>

    @include('layouts._head')

    @include('layouts._js_global')

    @yield('head')

    @yield('javascript')
    
    <style>
        @page {
            size:A4;
            margin: 25px;
            border:none;
        }
    </style>

</head>
<body class="{{ $skinClass }} layout-boxed sidebar-mini">

<div class="wrapper">

    <header class="main-header  print-hide">

        <a href="{{ auth()->check() ? route('dashboard.index') : '#' }}" class="logo">
            <span class="logo-lg">{{ config('fi.headerTitleText') }}</span>
        </a>

        <nav class="navbar navbar-static-top" role="navigation">

            @yield('header')

        </nav>
    </header>


    <div class="content-wrapper content-wrapper-public">
        @yield('content')
    </div>

</div>

<div id="modal-placeholder"></div>

</body>
</html>