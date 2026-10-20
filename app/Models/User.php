<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Aurora\Models\User as Authenticatable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'locale', 'theme', 'password', 'avatar', 'job_title', 'is_active'])]
#[Hidden(['password', 'remember_token', 'app_authentication_recovery_codes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
