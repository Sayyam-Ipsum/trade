<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Easy Option">

    <!-- ========== Page Title ========== -->
    <title>BTC Ride - @yield('page-title')</title>
    <link rel="shortcut icon" href="assets/site/img/favicon.png" type="image/x-icon">
    <!-- ========== Start Stylesheet ========== -->
    <link href="{{asset('assets/site/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/site/css/style.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    @yield('styles')
</head>

<body>

<div class="container p-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{url('/')}}">
                <img src="{{asset('assets/site/img/logo-2.png')}}" width="120px" height="60px">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white me-3" href="{{url('/')}}#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{url('/')}}#faq">FAQ</a>
                    </li>
                </ul>
                <div>
                    <a href="{{url('login')}}" class="btn btn-secondary btn-lg btn-login px-4 py-2 me-2">Log in</a>
                    <a href="{{url('register')}}" class="btn btn-lg btn-success btn-register px-4 py-2">Sign up</a>
                </div>
            </div>
        </div>
    </nav>
</div>
<hr style="background: gray; margin: 0;">
