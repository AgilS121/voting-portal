<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
   public function index()
    {
        $activeElections = Election::active()->with('candidates')->get();
        $upcomingElections = Election::upcoming()->with('candidates')->get();
        $completedElections = Election::ended()->with('candidates')->get(); // Ganti completed() jadi ended()

        return view('elections.index', compact('activeElections', 'upcomingElections', 'completedElections'));
    }


    public function show(Election $election)
    {
        $election->load('candidates');
        
        return view('elections.show', compact('election'));
    }
}