<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ✅ Custom primary key
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // ✅ Fields that can be mass assigned
    protected $fillable = [
        'u_name',
        'u_username',
        'u_password',
        'u_email',
        'u_phone',
        'u_role',
        'u_unit',
        'grn_id',
    ];

    // ✅ Fields to be hidden when serializing
    protected $hidden = [
        'u_password',
        'remember_token',
    ];

    // ✅ Cast fields (optional)
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    // ✅ Override password field for Auth
    public function getAuthPassword()
    {
        return $this->u_password;
    }

    // ✅ Optional: Override username field for login (if not using email)
    public function username()
    {
        return 'u_username';
    }
}
