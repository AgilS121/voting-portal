<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemNode extends Model
{
     protected $table = 'system_nodes';

    protected $primaryKey = 'node_id';
    public $incrementing = false; // Karena node_id bukan auto increment
    protected $keyType = 'string';

    protected $fillable = [
        'node_id',
        'name',
        'status',
        'is_online',
        'last_ping',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'last_ping' => 'datetime',
    ];
}