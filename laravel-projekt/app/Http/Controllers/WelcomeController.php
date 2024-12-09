<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Contest;

class WelcomeController extends Controller
{
    public function index()
    {
        $characterCount = Character::count();
        $contestCount = Contest::count();

        return view('welcome', [
            'characterCount' => $characterCount,
            'contestCount' => $contestCount
        ]);
    }
}
