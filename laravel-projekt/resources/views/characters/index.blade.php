<x-structure>
    <x-slot name="title">
        List of characters
    </x-slot>
    <h1>{{ Auth::user()->name }}'s characters</h1>

    @if (session('error'))
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('characters.create') }}" class="centeredCreateButton">NEW CHARACTER</a>

    @if($characters->isEmpty())
        <h2 class="nothing">You do not have any characters yet.<br>Create one now!</h2>
    @else
        <table class="centered-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Defense</th>
                    <th>Strength</th>
                    <th>Accuracy</th>
                    <th>Magic</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($characters as $character)
                <tr>
                    <td><a href="{{ route('characters.show', $character->id) }}">{{ $character->name }}</a></td>
                    <td>{{ $character->defence }}</td>
                    <td>{{ $character->strength }}</td>
                    <td>{{ $character->accuracy }}</td>
                    <td>{{ $character->magic }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-structure>
