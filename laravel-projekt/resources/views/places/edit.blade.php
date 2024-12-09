<x-structure>
    <x-slot name="title">
        Edit place
    </x-slot>
    <h1 class="pageName">Edit place</h1>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-warning">
            {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="container">
        <form class="formContainer" action="{{ route('places.update', $place->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <fieldset>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $place->name ) }}">
            </fieldset>
            <fieldset>
                <label for="image">Image</label>
                <div class="input-group">
                    <input type="file" class="form-control" id="image" name="image" aria-label="Upload">
                </div>
            </fieldset>
            <div>
                <h2>Current image</h2>
                <img src="{{ Str::startsWith($place->image, 'http') ? $place->image : Storage::url('images/' . $place->image) }}" alt="Image of {{ $place->name }}" title="Image of {{ $place->name }}" class="image-preview">
            </div>
            <button class="saveButton" type="submit">Save</button>
        </form>
    </div>
</x-structure>
