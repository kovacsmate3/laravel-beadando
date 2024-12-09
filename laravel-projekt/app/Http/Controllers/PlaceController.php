<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->admin) {
            return redirect('/characters')->with('error', 'Unauthorized access! Admin privileges required to access the Places Management page.');
        }

        $places = Place::all();
        return view('places.index', compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->admin) {
            return redirect()->route('characters.index')->with('error', 'Unauthorized access! Only administrators can access the Place Creation page.');
        }

        return view('places.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->admin) {
            return abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|unique:places,name|regex:/^[A-ZÁÉÍÓÖŐÚÜŰ]/',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5000'
        ], [
            'name.regex' => 'The name must start with an uppercase letter.'
        ]);

        $place = new Place();
        $place->name = $request->name;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fname = $file->hashName();
            Storage::disk('public')->put('images/'. $fname, $file->get());
            $place->image = $fname;
        }

        $place->save();

        return redirect()->route('places.index')->with('success', 'Place created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->admin) {
            $errorMessage = 'Unauthorized access! Only administrators can access the Place Edit page. Attempted access to Place ID: ' . $place->id;
            return redirect()->route('characters.index')->with('error', $errorMessage);
        }

        return view('places.edit', compact('place'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->admin) {
            return abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('places')->ignore($place->id),
                'regex:/^[A-ZÁÉÍÓÖŐÚÜŰ]/'
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000'
        ], [
            'name.regex' => 'The name must start with an uppercase letter.'
        ]);

        $place->name = $request->name;

        if ($request->hasFile('image')) {
            if ($place->image) {
                Storage::disk('public')->delete('images/' . $place->image);
            }

            $file = $request->file('image');
            $fname = $file->hashName();
            Storage::disk('public')->put('images/'. $fname, $file->get());
            $place->image = $fname;
        }

        $place->save();

        return redirect()->route('places.index')->with('success', "Place '{$place->name}' (ID: {$place->id}) updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->admin) {
            return abort(403, 'Unauthorized action.');
        }

        $contests = $place->contests;
        foreach ($contests as $contest) {
            $contest->delete();
        }

        if ($place->image) {
            Storage::disk('public')->delete('images/' . $place->image);
        }

        $place->delete();

        return redirect()->route('places.index')->with('success', "Place '{$place->name}' (ID: {$place->id}) deleted successfully!");
    }
}
