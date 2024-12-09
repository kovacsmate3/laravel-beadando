<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clash Craze - {{$title}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Quicksand&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
</head>

<body>
    <header>
        <img src="{{asset('logo.png')}}" alt="logo">
            <nav class="menu">
                <ul>
                    @if(Auth::check())
                        @if(Auth::user()->admin)
                        <li>
                            <a class="{{ request()->routeIs('places.index') ? 'active' : '' }}" href="{{ route('places.index') }}">Places</a>
                        </li>
                        @endif
                        <li>
                            <a class="{{ request()->routeIs('characters.index') ? 'active' : '' }}" href="{{ route('characters.index') }}">Characters</a>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}">
                                Profile
                            </a>
                        </li>
                        <li><a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Log out</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <li><a href="{{ route('login') }}">Log in</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endif
                </ul>
            </nav>
    </header>
    <main>
        {{$slot}}
    </main>
    <footer>
        <p>© 2024 Clash Craze | ELTE IK Szerveroldali Webprogramozás<br>
        All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
