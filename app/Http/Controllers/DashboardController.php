<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $activeElections = Election::active()->get();
        $myVotes = Vote::where('user_id', $user->id)
                      ->with(['election', 'candidate'])
                      ->latest()
                      ->take(5)
                      ->get();
        
        $availableElections = Election::active()
                                   ->whereNotIn('id', $myVotes->pluck('election_id'))
                                   ->get();

        $stats = [
            'total_elections' => Election::count(),
            'active_elections' => $activeElections->count(),
            'my_votes' => $myVotes->count(),
            'available_votes' => $availableElections->count(),
        ];

        return view('user.dashboard', compact('stats', 'activeElections', 'myVotes', 'availableElections'));
    }
}