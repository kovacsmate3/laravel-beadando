<x-structure>
    <x-slot name="title">
        Edit character
    </x-slot>
    <h1 class="pageName">Edit character</h1>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-warning">
            {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="container">
        <form class="formContainer" action="{{ route('characters.update', $character->id) }}" method="post">
            @csrf
            @method('PUT')
            <fieldset>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $character->name ) }}">
            </fieldset>
            @if(auth()->user()->admin)
            <div class="formCheckbox">
                <label for="enemy">Enemy character</label>
                <input class="enemy" type="checkbox" name="enemy" id="enemy" value="1" {{ old('enemy', $character->enemy) ? 'checked' : '' }} disabled>
            </div>
            @endif
            <fieldset>
                <label for="defence">Defence</label>
                <input type="number" name="defence" id="defence" value="{{ old('defence', $character->defence) }}">
            </fieldset>

            <fieldset>
                <label for="strength">Strength</label>
                <input type="number" name="strength" id="strength" value="{{ old('strength', $character->strength) }}">
            </fieldset>

            <fieldset>
                <label for="accuracy">Accuracy</label>
                <input type="number" name="accuracy" id="accuracy" value="{{ old('accuracy', $character->accuracy) }}">
            </fieldset>

            <fieldset>
                <label for="magic">Magic</label>
                <input type="number" name="magic" id="magic" value="{{ old('magic', $character->magic) }}">
            </fieldset>

            <button class="saveButton" type="submit">Save</button>
        </form>
    </div>
</x-structure>
