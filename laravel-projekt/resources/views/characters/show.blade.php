<x-structure>
    <x-slot name="title">
        {{ $character->name}}
    </x-slot>
    @if (session('error'))
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    @endif
    <div class="content">
        <h1 id="detailedCard">{{ $character->name }}</h1>
        <div id="details">
            <div>
                <div class="attributes">
                    <div class="card-defence">
                        <span class="icon">🛡</span> Defence: {{ $character->defence }}
                    </div>
                    <div class="card-strength">
                        <span class="icon">💪</span> Strength:  {{ $character->strength }}
                    </div>
                    <div class="card-accuracy">
                        <span class="icon">🎯</span> Accuracy:  {{ $character->accuracy }}
                    </div>
                    <div class="card-magic">
                        <span class="icon">🪄</span> Magic: {{ $character->magic }}
                    </div>
                </div>
                @if(auth()->user()->admin && $character->enemy)
                    <h2 class="enemy-heading">Enemy character alert!</h2>
                @else
                    <h2 class="ally-heading">Hero status confirmed!</h2>
                @endif
            </div>
        </div>
        <h2 id="matches">Contests</h2>
        <div id="contests">
            @if ($character->contests->isNotEmpty())
                    <ul>
                        @foreach ($character->contests as $contest)
                        <li>
                            <a class="contestLink" href="{{ route('contests.show', $contest->id) }}">Place: {{$contest->place->name}}<br>
                                @foreach ($contest->characters as $contestCharacter)
                                    @if ($contestCharacter->enemy && !$character->enemy)
                                        <span class="versus">VS</span> Enemy: {{$contestCharacter->name}}
                                    @elseif (!$contestCharacter->enemy && $character->enemy)
                                        <span class="versus">VS</span> Hero: {{$contestCharacter->name}}
                                    @endif
                                @endforeach
                            </a>
                        </li>
                        @endforeach
                    </ul>
            @else
                <h3>This character does not have any contests yet.</h3>
            @endif
        </div>
        <div id="characterButtons">
            <a href="{{ route('characters.edit', $character->id) }}" class="button">Edit</a>
            <form action="{{ route('characters.destroy', $character->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="danger" onclick="return confirm('Do you really want to delete?')">Delete</button>
            </form>
        </div>
        <form action="{{ route('contests.store') }}" method="post">
            @csrf
            <fieldset>
                <input type="hidden" name="character-id" id="character-id" value="{{ $character->id }}">
            </fieldset>
            <button id="newContestButton" type="submit">New Contest</button>
        </form>
    </div>
</x-structure>


