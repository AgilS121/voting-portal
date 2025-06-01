<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Election extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'elections';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'max_votes_per_user',
        'created_by',
        'sync_version'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'last_sync_at' => 'datetime',
    ];

    // protected $dates = ['deleted_at'];

    // Relationships
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function results()
    {
        return $this->hasMany(VoteResultCache::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'draft')
                    ->orWhere('start_date', '>', now());
    }

    public function scopeEnded($query)
    {
        return $query->where('status', 'ended')
                    ->orWhere('end_date', '<', now());
    }

    // Accessors & Mutators
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'badge-secondary',
            'active' => 'badge-success',
            'ended' => 'badge-danger'
        ];
        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               $this->start_date <= now() && 
               $this->end_date >= now();
    }

    public function getTotalVotesAttribute()
    {
        return $this->votes()->count();
    }

    // Methods
    public function updateSyncVersion()
    {
        $this->increment('sync_version');
        $this->update(['last_sync_at' => now()]);
    }

    public function activate()
    {
        $this->update(['status' => 'active']);
        $this->updateSyncVersion();
    }

    public function end()
    {
        $this->update(['status' => 'ended']);
        $this->updateSyncVersion();
    }
}