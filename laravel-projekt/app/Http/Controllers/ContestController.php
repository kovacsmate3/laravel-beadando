<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Character;
use App\Models\Place;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $character = Character::find($request->input('character-id'));

        if (auth()->check() &&
        $character->user_id === auth()->id()
        && !$character->enemy) {

            $place = Place::inRandomOrder()->first();

            $opponent = Character::where('id', '!=', $character->id)
                                ->where('enemy', '!=', $character->enemy)
                                ->inRandomOrder()
                                ->first();

            if (!$opponent) {
                return redirect()->back()->with('error', 'No suitable opponent found. Please try again later.');
            }

            $contest = new Contest([
                'user_id' => auth()->id(),
                'place_id' => $place->id,
                'win' => null,
                'history' => ''
            ]);

            $contest->save();
            $contest->characters()->attach($character->id, ['hero_hp' => 20, 'enemy_hp' => 20]);
            $contest->characters()->attach($opponent->id, ['hero_hp' => 20, 'enemy_hp' => 20]);

            return redirect()->route('contests.show', ['contest' => $contest->id]);

        } else {
            $message = 'Unable to start the contest. ';
            if (!auth()->check()) {
                return abort(403,'Unauthorized action.');
            } else if ($character->user_id !== auth()->id()) {
                $message .= 'You can only initiate contests with your own characters.';
            } else if ($character->enemy) {
                $message .= 'Enemy characters cannot initiate contests.';
            }
            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contest $contest)
    {
        if(!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->id();
        $userCharacters = Character::where('user_id', $userId)->pluck('id')->toArray();

        $place = $contest->place;
        $characters = $contest->characters()->withPivot('hero_hp', 'enemy_hp')->get();

        if (!$characters->whereIn('id', $userCharacters)->count()) {
            return redirect()->route('characters.index')->with('error', "You do not have permission to view this contest (ID: {$contest->id}). You are not the owner of any participating characters.");
        }

        $hero = $characters->firstWhere('enemy', false);
        $enemy = $characters->firstWhere('enemy', true);

        return view('contests.show', [
            'contest' => $contest,
            'placeName' => $place->name,
            'placeImage' => $place->image,
            'characters' => $characters,
            'hero' => $hero,
            'enemy' => $enemy
        ]);
    }

    /**
     * Handles an attack in a contest between two characters: a hero and an enemy.
     *
     * This method coordinates an attack round within a specified contest. It processes an attack by the hero,
     * followed potentially by a counterattack by the enemy, depending on the ongoing status of the contest.
     *
     * @param string $id The unique identifier of the contest to perform the attack in.
     * @param string $attackType The type of attack initiated by the hero, which can be 'melee', 'ranged', or 'special'.
    */
    public function attack(string $id, string $attackType) {
        $contest = Contest::with('characters')->findOrFail($id);

        if ($contest->win !== null) {
            return redirect()->route('contests.show', $id);
        }

        $hero = $contest->characters->firstWhere('enemy', false);
        $enemy = $contest->characters->firstWhere('enemy', true);

        // Perform attack by hero
        $heroDamage = $this->calculateDamage($attackType, $hero, $enemy);
        $this->updateHpAndHistory($contest, $hero, $enemy, $attackType, $heroDamage);

        if ($contest->win === null) {
            // Perform attack by enemy
            $attackTypes = ['melee', 'ranged', 'special'];
            $enemyAttackType = $attackTypes[array_rand($attackTypes)];
            $enemyDamage = $this->calculateDamage($enemyAttackType, $enemy, $hero);
            $this->updateHpAndHistory($contest, $enemy, $hero, $enemyAttackType, $enemyDamage);
        }

        return redirect()->route('contests.show', $id);
    }

    /**
     * Calculates the damage inflicted by the attacker on the defender.
     *
     * @param string $type The type of attack ('melee', 'ranged', 'special').
     * @param object $attacker The attacking character, with properties hp, defence, strength, accuracy, magic.
     * @param object $defender The defending character, with properties hp, defence, strength, accuracy, magic.
     * @return float The calculated damage.
    */
    private function calculateDamage(string $type, Character $attacker, Character $defender) {
        $baseDamage = match($type) {
            'melee' => ($attacker->strength * 0.7 + $attacker->accuracy * 0.1 + $attacker->magic * 0.1) - $defender->defence,
            'ranged' => ($attacker->strength * 0.1 + $attacker->accuracy * 0.7 + $attacker->magic * 0.1) - $defender->defence,
            'special' => ($attacker->strength * 0.1 + $attacker->accuracy * 0.1 + $attacker->magic * 0.7) - $defender->defence,
            default => 0
        };

        return max($baseDamage, 0);
    }

    /**
     * Updates the hit points (HP) of the defender character, logs the attack in the contest's history,
     * and checks if the contest has concluded based on the HP after the attack.
     *
     * @param Contest $contest The contest object representing the current contest.
     * @param Character $attacker The character who is performing the attack.
     * @param Character $defender The character who is receiving the attack.
     * @param string $attackType The type of attack being performed ('melee', 'ranged', 'special').
     * @param float $damage The amount of damage dealt in this attack. Must be a non-negative number.
    */
    private function updateHpAndHistory(Contest $contest, Character $attacker, Character $defender, string $attackType, float $damage)
    {
        $hpField = $attacker->enemy ? 'hero_hp' : 'enemy_hp';

        $currentHp = $defender->pivot->{$hpField};
        $newHp = max($currentHp - $damage, 0);

        $defender->pivot->{$hpField} = $newHp;
        $defender->pivot->save();

        $actionDetail = "{$attacker->name} (" . ($attacker->enemy ? 'enemy' : 'hero') . ")  performed a {$attackType} attack causing {$damage} damage to {$defender->name}.\n";
        $contest->history .= $actionDetail;
        $contest->save();

        if ($newHp <= 0) {
            $contest->win = $defender->enemy; // True if hero wins, false if enemy wins
            $contest->save();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contest $contest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contest $contest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contest $contest)
    {
        //
    }
}
