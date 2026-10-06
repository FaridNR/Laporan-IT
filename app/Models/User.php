<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isProgramer(): bool
    {
        return $this->role === 'programer';
    }

    public function isStaffIt(): bool
    {
        return $this->role === 'staff_it';
    }

    public function isKepalaIt(): bool
    {
        return $this->role === 'kepala_it';
    }

    public function workReports(): HasMany
    {
        return $this->hasMany(WorkReport::class);
    }
}
