<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'votes';

    protected $fillable = [
        'election_id',
        'candidate_id',
        'user_id',
        'node_id',
        'vote_hash',
        'ip_address',
        'user_agent',
        'voted_at',
        'sync_status',
        'sync_version'
    ];

    protected $casts = [
        'voted_at' => 'datetime',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('sync_status', 'pending');
    }

    public function scopeSynced($query)
    {
        return $query->where('sync_status', 'synced');
    }

    // Methods
    public function generateHash()
    {
        return hash('sha256', $this->user_id . $this->election_id . $this->candidate_id . $this->voted_at);
    }

    public function markAsSynced()
    {
        $this->update(['sync_status' => 'synced']);
    }
}