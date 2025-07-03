
<!DOCTYPE html>
<html class="h-100" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <meta name="_url" content="{!! URL::to('/') !!}" />
    <title> AXOCALL - Secure Communication Platform </title>

    <link rel="shortcut icon" href="{{ asset('assets/axocall/icons/logo.svg') }}" type="image/x-icon">

    <link
      href="{{ asset('assets/system/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}"
      rel="stylesheet"
    />
    <link href="{{ asset('assets/system/plugins/toastr/css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/system/plugins/jquery-asColorPicker-master/css/asColorPicker.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/system/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/system/css/style.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/system/css/override.css') }}" type="text/css" rel="stylesheet">
</head>

<body class="h-100">

    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>

    <div id="main-wrapper">

        <div class="nav-header">
            <div class="brand-logo">
                <a href="index.html">
                    <b class="logo-abbr"><img src="{{ asset('assets/axocall/icons/logo.svg') }}" alt=""> </b>
                    <span class="logo-compact"><img src="./images/logo-compact.png" alt=""></span>
                    <span class="brand-title">
                        <h1>AXOCALL</h1>
                    </span>
                </a>
            </div>
        </div>

        <div class="header">    
            <div class="header-content clearfix">
                
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
                {{-- <div class="header-left">
                    <div class="input-group icons">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0 pr-2 pr-sm-3" id="basic-addon1"><i class="mdi mdi-magnify"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Search Dashboard" aria-label="Search Dashboard">
                        <div class="drop-down animated flipInX d-md-none">
                            <form action="#">
                                <input type="text" class="form-control" placeholder="Search">
                            </form>
                        </div>
                    </div>
                </div> --}}
                <div class="header-right">
                    <ul class="clearfix">
                        
                        
                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                                <span class="activity"></span>
                                <img src="{{ asset('assets/axocall/icons/logo.svg') }}" height="40" width="40" alt="UI">
                            </div>
                            <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <a href="{{ route('maintenance.settings') }}">
                                                <i class="icon-envelope-open"></i> <span>Settings</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('maintenance.profile') }}"><i class="icon-user"></i> <span>Profile</span></a>
                                        </li>                                        
                                        <hr class="my-2">
                                        <li><a href="page-login.html"><i class="icon-key"></i> <span>Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @include('partials.admin.sidebar')

        <div class="content-body">  
            <div class="row page-titles mx-0">
                <div class="col-6">
                    <h1 class="module-title">{{ $title }}</h1>
                    <p class="module-description">{{ $description }}</p>
                </div>
                <div class="col-6">
                    <x-right-panel xtype='{{ $panel_type }}'/>
                </div>
            </div>