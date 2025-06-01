<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'candidates';

    protected $fillable = [
        'election_id',
        'name',
        'description',
        'image_url',
        'order_number',
        'sync_version'
    ];

    // protected $dates = ['deleted_at'];

    // Relationships
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Accessors
    public function getVoteCountAttribute()
    {
        return $this->votes()->count();
    }

    public function getVotePercentageAttribute()
    {
        $totalVotes = $this->election->total_votes;
        if ($totalVotes == 0) return 0;
        return round(($this->vote_count / $totalVotes) * 100, 2);
    }

    public function getImageUrlAttribute($value)
    {
        if (!$value) return asset('images/default-candidate.png');
        if (str_starts_with($value, 'http')) return $value;
        return asset('uploads/candidates/' . $value);
    }

    // Methods
    public function updateSyncVersion()
    {
        $this->increment('sync_version');
    }
}