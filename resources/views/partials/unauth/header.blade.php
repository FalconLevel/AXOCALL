
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

    <link rel="stylesheet" href="{{ asset('assets/system/plugins/fontawesome/css/all.min.css') }}" type="text/css">
    <link href="{{ asset('assets/system/plugins/toastr/css/toastr.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/system/css/style.css') }}" type="text/css">

    <link href="{{ asset('assets/system/css/override.css') }}" type="text/css" rel="stylesheet">
</head>

<body class="h-100">