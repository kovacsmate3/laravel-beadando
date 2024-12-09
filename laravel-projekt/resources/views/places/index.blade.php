<x-structure>
    <x-slot name="title">
        List of places
    </x-slot>
    <h1>Places</h1>

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

    <a href="{{ route('places.create') }}" class="centeredCreateButton">NEW PLACE</a>

    @if($places->isEmpty())
    <h2 class="nothing">There aren't any places yet.<br>Create one now!</h2>
    @else
        <table class="placesTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="placesTableBody">
                @foreach ($places as $place)
                <tr>
                    <td>{{ $place->name }}</td>
                    <td>
                        <img src="{{ Str::startsWith($place->image, 'http') ? $place->image : Storage::url('images/' . $place->image) }}" alt="Image of {{ $place->name }}" title="Image of {{ $place->name }}" style="width: 150px; height: auto;">
                    </td>
                    <td class="placeButtonsTd">
                        <div class="action-buttons">
                            <a href="{{ route('places.edit', $place->id) }}" class="button">Edit</a>
                            <form action="{{ route('places.destroy', $place->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger" onclick="return confirm('Do you really want to delete?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-structure>
