<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasUuids;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'cv_path',
        'application_status',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'application_status' => 'array',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
