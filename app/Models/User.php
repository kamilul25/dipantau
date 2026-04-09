<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public const ROLE_SUPERADMIN = 'superadmin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PUBLIK = 'publik';


    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPublik(): bool
    {
        return $this->role === self::ROLE_PUBLIK;
    }

    public function scopeSuperAdmin($query)
    {
        return $query->where('role', self::ROLE_SUPERADMIN);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    public function scopePublik($query)
    {
        return $query->where('role', self::ROLE_PUBLIK);
    }

     public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_SUPERADMIN => 'Super Administrator',
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_PUBLIK => 'Publik',
            default => 'Unknown',
        };
    }
}