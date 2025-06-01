<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Election;

class HomeController extends Controller
{
    public function index()
    {
        $activeElections = Election::active()->latest()->take(3)->get();
        $upcomingElections = Election::upcoming()->latest()->take(3)->get();
        
        return view('welcome', compact('activeElections', 'upcomingElections'));
    }
}