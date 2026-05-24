<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['username', 'password', 'roles', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'roles' => 'array', // TAMBAHAN
        ];
    }

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class, 'user_id', 'id');
    }

    // Helper cek role
    public function hasRole($role)
    {
        return in_array(
            $role,
            $this->roles ?? []
        );
    }
}