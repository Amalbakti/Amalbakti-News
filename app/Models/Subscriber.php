<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscriber extends Model
{
    use Notifiable;
    protected $fillable = [
        'email',
        'token',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($subscriber) {
            $subscriber->token = Str::random(32);
        });
    }

    // required for sending notifications to subscriber
    public function routeNotificationForMail()
    {
        return $this->email;
    }
}
