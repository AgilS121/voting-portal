<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
     use HasFactory, SoftDeletes;

    protected $table = 'users';


    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'is_verified',
        'fcm_token',
        'role',
        'sync_version'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_admin' => 'boolean',
    ];

    // protected $dates = ['deleted_at'];

    // Relationships
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function createdElections()
    {
        return $this->hasMany(Election::class, 'created_by');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    // Accessors
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    public function getRoleBadgeAttribute()
    {
        $badges = [
            'user' => 'badge-primary',
            'admin' => 'badge-warning',
            'super_admin' => 'badge-danger'
        ];
        return $badges[$this->role] ?? 'badge-secondary';
    }

    // Methods
    public function hasVotedIn($electionId)
    {
        return $this->votes()->where('election_id', $electionId)->exists();
    }

    public function updateSyncVersion()
    {
        $this->increment('sync_version');
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}