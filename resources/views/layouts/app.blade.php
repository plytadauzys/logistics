<head>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="{{ asset('sass/style.scss') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!--<script src="asset('js/sorttable.js') "></script>-->
    <title>@yield('title')</title>
    <meta charset="utf-8">
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <p></p>
            <br>
            <p></p>
            @if(session('user') || session('admin'))
                <li class="nav-item">
                    <a class="nav-link" href="/"><button id="homeBtn" class="btn btn-outline-success me-2" type="button">Namai</button></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/clients"><button id="clientBtn" class="btn btn-outline-success me-2" type="button">Klientai</button></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/suppliers"><button id="supplierBtn" class="btn btn-outline-success me-2" type="button">Tiekėjai</button></a>
                    <!--<a class="nav-link" href="/suppliers">Tiekėjai</a>-->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/expeditions"><button id="expBtn" class="btn btn-outline-success me-2" type="button">Ekspedicijos</button></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/expeditionHistory"><button id="expHistBtn" class="btn btn-outline-success me-2" type="button">Ekspedicijų istorija</button></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/ldm"><button id="ldmBtn" class="btn btn-outline-success me-2" type="button">Krovimo metrų skaičiuoklė</button></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/settings"><button id="warnSettings" class="btn btn-outline-success me-2" type="button">Įspėjimų nustatymai</button></a>
                </li>
                @if(session('user'))
                    <li class="nav-item">
                        <a class="nav-link" href="/logoutManager"><button id="logoutBtn" class="btn btn-outline-success me-2" type="button">Atsijungti</button></a>
                    </li>
                @elseif(session('admin'))
                    <li class="nav-item">
                        <a class="nav-link" href="/logoutAdmin"><button id="loginBtn" class="btn btn-outline-success me-2" type="button">Atsijungti</button></a>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</nav>
<p id="markDanger" hidden></p>
<p id="markWarning" hidden></p>
<p id="markClient" hidden></p>
<p id="markSupplier" hidden></p>
<main>
    @yield('content')
</main>
