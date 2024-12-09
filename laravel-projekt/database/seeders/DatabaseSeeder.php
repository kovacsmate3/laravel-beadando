<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(7)->create();
        $administrators = collect();
        $numberOfAdmins = rand(1, 3);
        for ($i = 1; $i <= $numberOfAdmins; $i++) {
            $admin = User::factory()->create([
                'admin' => true,
                'name' => "Admin{$i}",
                'email' => "admin{$i}@example.com",
                'password' => "admin"
            ]);

            $administrators->push($admin);
            $users->push($admin);
        }

        //ENEMIES ONLY
        $enemies = Character::factory(rand(5, 10))->create(['enemy' => true]);
        $enemies->each(function($e) use (&$administrators) {
            $e->user()->associate($administrators->random())->save();
        });

        //HEROES
        $heroes = Character::factory(rand(15, 25))->create();
        $heroes->each(function($h) use (&$users) {
            $h->user()->associate($users->random())->save();
        });

        $places = Place::factory(rand(5,10))->create();

        $contests = Contest::factory(rand(10,15))->create();
        $contests->each(function($c) use (&$users, &$places, &$heroes, &$enemies) {
            $c->user()->associate($users->random())->save();
            $c->place()->associate($places->random())->save();

            $hero = $heroes->random();
            $enemy = $enemies->random();

            if (is_null($c->win)) {
                $heroHp = 20;
                $enemyHp = 20;
            } else {
                $heroHp = $c->win ? 20 : 0;
                $enemyHp = $c->win ? 0 : 20;
            }

            $c->characters()->attach([
                $hero->id => ['hero_hp' => $heroHp, 'enemy_hp' => $enemyHp],
                $enemy->id => ['enemy_hp' => $enemyHp, 'hero_hp' => $heroHp]
            ]);
        });
    }
}
