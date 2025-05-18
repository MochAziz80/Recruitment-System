<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;


class Application extends Model implements AuditableContract
{
    use SoftDeletes, HasUuids, Auditable;

    protected $fillable = [
        'user_id',
        'job_id',
        'status',
        'notes',
        'applied_at',
        'job_title',      
        'job_snapshot', 
    ];

    protected $auditInclude = ['status', 'notes', 'applied_at']; 

    protected $casts = [
        'job_snapshot' => 'array',
        'applied_at' => 'datetime',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke job
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}

