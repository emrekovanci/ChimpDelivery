<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        .toast-container { position: fixed; right: 20px; top: 20px; }
        .toast:not(.showing):not(.show) { display: none !important; }
    </style>
</head>
<body>

<div class="container-fluid">
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand font-weight-bold" href="/dashboard">
            <img src="{{ asset('Talus_icon.ico') }}" alt="..." height="36"/>
            Dashboard
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto nav-pills">
                <li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard">Apps <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item {{ (request()->is('dashboard/add-app-info')) ? 'active' : '' }}">
                    <a class="nav-link font-weight-bold" href="/dashboard/add-app-info">Create App</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-italic" href="/dashboard/clear-cache">Clear Cache</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>
</div>

<!-- errors_begin !-->
<div class="container-fluid">
    @if($errors->any())
        <br />
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
<!-- errors_end !-->

<!-- contents_start !-->
<section class="page-content">
    @yield('content')
</section>
<!-- contents_end !-->

<!-- toast_begin !-->
<div class="toast-container">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
        <div class="toast-header bg-success">
            <img src="{{ asset('Talus_icon.ico') }}" class="rounded mr-2" alt="..." width="16" height="16">
            <strong class="mr-auto text-white">Talus Dashboard</strong>
            <small class="text-white">7 mins ago</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" onclick="setToastCookie()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            Welcome to Talus Dashboard, have fun :) Thanks for all feedbacks and have a nice day.
        </div>
    </div>
</div>
<!-- toast_end !-->

<!-- footer_begin !-->
<!--
<footer class="page-footer font-small blue fixed-bottom">
    <div class="footer-copyright text-center py-1 text-muted">
        <span class="font-weight-bold font-italic">Talus Studio</span>
    </div>
</footer>
</body>
</html>
!-->
<!-- footer_end !-->
</body>

<section class="scripts">
<script type="text/javascript">
    $(document).ready(function() {
        console.log("talus-toast-cookie:" + getCookie('talus-toast-cookie'));

        if (!getCookie('talus-toast-cookie')) {
            $('.toast').toast('show');
        }
    });

    function setToastCookie() {
        setCookie('talus-toast-cookie', 1, 1);
    }

    function setCookie(name,value,days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    function expireCookie(name) {
        document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
</script>

    @yield('scripts')
</section>
