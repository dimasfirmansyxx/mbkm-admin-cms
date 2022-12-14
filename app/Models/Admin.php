<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'username',
        'password',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role() { return $this->belongsTo(Role::class, 'role_id', 'id'); }
}