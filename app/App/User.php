<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static User create(array $array)
 * @method User assignRole(...$roles)
 * @method User givePermissionTo(...$permission)
 * @method User revokePermissionTo($permission)
 * @property int $id
 * @property string $email
 * @property string $name
 */
class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    public const EMAIL = 'email';
    public const NAME = 'name';
    public const PASSWORD = 'password';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
