<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{

    protected $table = 'sync_logs';
    
     protected $fillable = [
        'node_id',
        'table_name',
        'operation',
        'record_id',
        'sync_version',
        'status',
        'error_message',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForNode($query, $nodeId)
    {
        return $query->where('node_id', $nodeId);
    }

    // Methods
    public function markAsProcessed()
    {
        $this->update([
            'status' => 'success',
            'processed_at' => now()
        ]);
    }

    public function markAsFailed($errorMessage)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'processed_at' => now()
        ]);
    }
}