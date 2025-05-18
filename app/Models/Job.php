<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;


class Job extends Model implements Auditable
{
    use SoftDeletes, HasUuids, HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title',
        'description',
        'location',
        'requirements',
        'is_active',
        'posted_at',
        'uploaded_by'
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_active' => 'boolean',
        'posted_at' => 'datetime',
    ];

    // Relasi: Job memiliki banyak application
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function postedBy()
{
    return $this->belongsTo(User::class, 'uploaded_by');
}

}
