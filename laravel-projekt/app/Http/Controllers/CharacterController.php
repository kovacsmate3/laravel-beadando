<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $enemies = [];
        if (auth()->user()->admin) {
            $enemies = Character::where('enemy', true)->get();
        }

        $heroes = Character::where('user_id', '=', auth()->user()->id)->get();

        $characters = $heroes->merge($enemies);

        return view('characters.index', compact('characters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return view('characters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|unique:characters,name|regex:/^[A-ZÁÉÍÓÖŐÚÜŰ]/',
            'defence' => 'required|integer|min:0|max:3',
            'strength' => 'required|integer|min:0|max:20',
            'accuracy' => 'required|integer|min:0|max:20',
            'magic' => 'required|integer|min:0|max:20',
        ], [
            'name.regex' => 'The name must start with an uppercase letter.'
        ]);

        $sumOfAbilityPoints = $request->defence+$request->strength+$request->accuracy+$request->magic;

        if ($sumOfAbilityPoints != 20) {
            return redirect()->back()->withErrors(["total" => "Sum of ability points must exactly equal 20."])->withInput();
        }

        $character = new Character();
        $character->user_id = auth()->id();
        $character->name = $request->name;
        $character->enemy = $request->input('enemy', 0);
        $character->defence = $request->defence;
        $character->strength = $request->strength;
        $character->accuracy = $request->accuracy;
        $character->magic = $request->magic;
        $character->save();

        return redirect()->route('characters.index')->with('success', "Character '{$character->name}' (ID: {$character->id}) created successfully!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Character $character)
    {
        if (auth()->check() &&
        $character->user_id === auth()->id() ||
        auth()->check() &&
        auth()->user()->admin &&
        ($character->user_id === auth()->id() || $character->enemy)) {
            return view('characters.show', compact('character'));
        } else {
            $errorMessage = 'Unauthorized access to show character details! Attempted access to Character ID: ' . $character->id;
            return redirect()->route('characters.index')->with('error', $errorMessage);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Character $character)
    {
        if (auth()->check() &&
        $character->user_id === auth()->id() ||
        auth()->check() &&
        auth()->user()->admin &&
        ($character->user_id === auth()->id() || $character->enemy)) {
            return view('characters.edit', compact('character'));
        } else {
            $errorMessage = 'Unauthorized access to edit character details! Attempted access to Character ID: ' . $character->id;
            return redirect()->route('characters.index')->with('error', $errorMessage);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Character $character)
    {
        if (auth()->check() &&
        $character->user_id === auth()->id() ||
        auth()->check() &&
        auth()->user()->admin &&
        ($character->user_id === auth()->id() || $character->enemy)) {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('characters')->ignore($character->id),
                    'regex:/^[A-ZÁÉÍÓÖŐÚÜŰ]/'
                ],
                'defence' => 'required|integer|min:0|max:3',
                'strength' => 'required|integer|min:0|max:20',
                'accuracy' => 'required|integer|min:0|max:20',
                'magic' => 'required|integer|min:0|max:20',
            ], [
                'name.regex' => 'The name must start with an uppercase letter.'
            ]);

            $sumOfAbilityPoints = $request->defence+$request->strength+$request->accuracy+$request->magic;

            if ($sumOfAbilityPoints != 20) {
                return redirect()->back()->withErrors(["total" => "Sum of ability points must exactly equal 20."])->withInput();
            }

            $character->user_id = auth()->id();
            $character->name = $request->name;
            $character->defence = $request->defence;
            $character->strength = $request->strength;
            $character->accuracy = $request->accuracy;
            $character->magic = $request->magic;
            $character->save();

            return redirect()->route('characters.index')->with('success', "Character '{$character->name}' (ID: {$character->id}) updated successfully!");
        } else {
            return abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Character $character)
    {
        if (auth()->check() &&
        $character->user_id === auth()->id() ||
        auth()->check() &&
        auth()->user()->admin &&
        ($character->user_id === auth()->id() || $character->enemy)) {
            $contests = $character->contests;
            foreach ($contests as $contest) {
                $contest->delete();
            }

            $character->delete();
            return redirect()->route('characters.index')->with('success', "Character '{$character->name}' (ID: {$character->id}) deleted successfully!");
        }
        //Igazából ez az else elágazás meg sem kéne, hogy történjen.
        else {
            return abort(403, 'Unauthorized action.');
        }
    }
}
