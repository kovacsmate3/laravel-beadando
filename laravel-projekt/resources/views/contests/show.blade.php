<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clash Craze - Contest#{{$contest->id}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Quicksand&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('contest_details.css')}}">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
</head>

<body style="background-image: url({{ Str::startsWith($placeImage, 'http') ? $placeImage : Storage::url('images/' . $placeImage) }}); background-size: cover; ">
    <header>
        <img src="{{asset('logo.png')}}" alt="logo">
            <nav id="menu">
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
        <h1 class="text-center">Contest at <span id="placeName">{{ $placeName }}</span> Arena</h1>
        <div class="container">
            <div class="row">
                @foreach ($contest->characters as $contestCharacter)
                    @php
                        $isEnemy = $contestCharacter->enemy;
                        $cardClass = $isEnemy ? 'darkBg' : 'lightBg';
                        $cardTitle = $isEnemy ? 'Enemy' : 'Hero';
                        $hpType = $isEnemy ? 'enemy_hp' : 'hero_hp';
                    @endphp
                    <div class="col-6 {{$cardClass}}">
                        <h3 class="text-center">{{ $cardTitle }}: {{ $contestCharacter['name'] }}</h3>
                        <div>
                            <div class="attributes text-center">
                                <div>
                                    <span class="icon">🛡</span> Defence: {{ $contestCharacter['defence'] }}
                                </div>
                                <div>
                                    <span class="icon">💪</span> Strength: {{ $contestCharacter['strength'] }}
                                </div>
                                <div>
                                    <span class="icon">🎯</span> Accuracy: {{ $contestCharacter['accuracy'] }}
                                </div>
                                <div>
                                    <span class="icon">🪄</span> Magic: {{ $contestCharacter['magic'] }}
                                </div>
                                <div>
                                    <span class="icon">❤️</span> HP: {{ $contestCharacter->pivot->$hpType }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row history">
                <h4 class="text-left"><u>Contest history</u></h4>
                <p class="text-justify contestEvents">{!! nl2br($contest->history) !!}</p>
            </div>
        </div>
        @if($contest->win === null)
            <div class="action-buttons">
                <a class="button" href="{{ route('contest.attack', ['id' => $contest->id, 'attackType' => 'melee']) }}">Melee</a>
                <a class="button" href="{{ route('contest.attack', ['id' => $contest->id, 'attackType' => 'ranged']) }}">Ranged</a>
                <a class="button" href="{{ route('contest.attack', ['id' => $contest->id, 'attackType' => 'special']) }}">Special</a>
            </div>
        @else
            @if($hero->pivot->hero_hp > $enemy->pivot->enemy_hp)
                <div class="text-center" id="contestWon">
                    <i>Hero <span class="black">{{ $hero->name }}</span></i> <span class="result">won</span> against <i><span class="black">{{ $enemy->name }}</span> enemy</i>.
                </div>
            @else
                <div class="text-center" id="contestLost">
                    <i>Hero <span class="black">{{ $hero->name }}</i></span> has <span class="result">lost</span> to <i>enemy <span class="black">{{ $enemy->name }}</span></i>.
                </div>
            @endif
        @endif

    </main>
    <footer>
        <p>© 2024 Clash Craze | ELTE IK Szerveroldali Webprogramozás<br>
        All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

