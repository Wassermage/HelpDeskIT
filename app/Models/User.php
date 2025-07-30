<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    /**
     * Get the user's initials.
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's support groups.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    /**
     * Get the user's roles.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the user's permissions.
     */
    public function permissions()
    {
        return $this->roles->flatMap->permissions->unique('id');
    }

    /**
     * Check if user is member of a given role.
     * 
     * @param string $roleName
     * @return bool True if the user has the role , false otherwise.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    /**
     * Check if the user has a given permission.
     * 
     * @param string $permissionName
     * @return bool True if the user has the permission, false otherwise.
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->contains('name', $permissionName);
    }
    
    /**
     * Assign the user to a default role at creation.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            $defaultRole = Role::where('name', 'user')->first();

            if ($defaultRole) {
                $user->roles()->attach($defaultRole->id, [
                    'assigned_at' => now(),
                    'assigned_by' => auth()->id() ?? null,
                ]);
            }
        });
    }
}
