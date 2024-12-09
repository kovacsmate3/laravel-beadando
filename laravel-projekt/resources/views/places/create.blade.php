<x-structure>
    <x-slot name="title">
        New place
    </x-slot>
    <h1 class="pageName">Create new place</h1>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-warning">
            {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="container">
        <form class="formContainer" action="{{ route('places.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <fieldset>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}">
            </fieldset>
            <fieldset>
                <label for="image">Image</label>
                <div class="input-group">
                    <input type="file" class="form-control" id="image" name="image" aria-label="Upload">
                </div>
            </fieldset>
            <button class="saveButton" type="submit">Create</button>
        </form>
    </div>
</x-structure>
