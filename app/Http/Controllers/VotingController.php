<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VotingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->is_verified) {
            return redirect('/dashboard')->with('error', 'Your account needs to be verified before you can vote.');
        }

        $votedElectionIds = Vote::where('user_id', $user->id)->pluck('election_id');
        
        $availableElections = Election::active()
                                   ->whereNotIn('id', $votedElectionIds)
                                   ->with('candidates')
                                   ->get();

        return view('voting.index', compact('availableElections'));
    }

    public function show(Election $election)
    {
        $user = auth()->user();
        
        if (!$user->is_verified) {
            return redirect('/dashboard')->with('error', 'Your account needs to be verified before you can vote.');
        }

        Log::info($election->status);

        if ($election->status != 'active') {
            return redirect('/vote')->with('error', 'This election is not currently active.');
        }

        $hasVoted = Vote::where('user_id', $user->id)
                       ->where('election_id', $election->id)
                       ->exists();

        if ($hasVoted) {
            return redirect('/vote')->with('error', 'You have already voted in this election.');
        }

        $candidates = $election->candidates()->orderBy('id')->get();

        return view('voting.election', compact('election', 'candidates'));
    }

    public function submit(Request $request, Election $election)
    {
        $user = auth()->user();
        
        if (!$user->is_verified) {
            return redirect('/dashboard')->with('error', 'Your account needs to be verified before you can vote.');
        }

        if ($election->status != 'active') {
            return redirect('/vote')->with('error', 'This election is not currently active.');
        }

        $hasVoted = Vote::where('user_id', $user->id)
                       ->where('election_id', $election->id)
                       ->exists();

        if ($hasVoted) {
            return redirect('/vote')->with('error', 'You have already voted in this election.');
        }

        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        $candidate = Candidate::findOrFail($request->candidate_id);
        
        if ($candidate->election_id != $election->id) {
            return redirect()->back()->with('error', 'Invalid candidate selection.');
        }

        DB::transaction(function () use ($user, $election, $candidate) {
            Vote::create([
                'user_id' => $user->id,
                'election_id' => $election->id,
                'candidate_id' => $candidate->id,
                'node_id' => env('NODE_ID'),
                'ip_address' => request()->ip(),
                'vote_hash' => $this->generateVoteHash($user->id, $election->id, $candidate->id),
            ]);
        });

        return redirect()->route('vote.success', $election)->with('success', 'Your vote has been recorded successfully!');
    }

    private function generateVoteHash($userId, $electionId, $candidateId)
    {
        return hash('sha256', $userId . '|' . $electionId . '|' . $candidateId . '|' . now());
    }


    public function success(Election $election)
    {
        $user = auth()->user();
        
        $vote = Vote::where('user_id', $user->id)
                   ->where('election_id', $election->id)
                   ->with('candidate')
                   ->first();

        if (!$vote) {
            return redirect('/vote');
        }

        return view('voting.success', compact('election', 'vote'));
    }

    public function history()
    {
        $user = auth()->user();
        
        $votes = Vote::where('user_id', $user->id)
                    ->with(['election', 'candidate'])
                    ->latest()
                    ->paginate(10);

        return view('voting.history', compact('votes'));
    }
}