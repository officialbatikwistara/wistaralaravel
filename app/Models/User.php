<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','phone','password'];

    protected $hidden = ['password','remember_token'];

    /**
     * Skip email verification
     */
    public function sendEmailVerificationNotification()
    {
        // Do nothing - skip email verification
        \Log::info('Email verification skipped for user', ['user_id' => $this->id]);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class, 'user_id');
    }

}
