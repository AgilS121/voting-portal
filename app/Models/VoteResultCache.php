<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteResultCache extends Model
{
    protected $table = 'vote_results_cache';

    protected $fillable = [
        'election_id',
        'candidate_id',
        'vote_count',
        'percentage'
    ];

    // Relationships
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    // Methods
    public static function updateResults($electionId)
    {
        $election = Election::find($electionId);
        $totalVotes = $election->votes()->count();

        foreach ($election->candidates as $candidate) {
            $voteCount = $candidate->votes()->count();
            $percentage = $totalVotes > 0 ? ($voteCount / $totalVotes) * 100 : 0;

            static::updateOrCreate(
                [
                    'election_id' => $electionId,
                    'candidate_id' => $candidate->id
                ],
                [
                    'vote_count' => $voteCount,
                    'percentage' => round($percentage, 2)
                ]
            );
        }
    }
}