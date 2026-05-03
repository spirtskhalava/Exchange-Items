<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'company_id',
        'dealer_id',
        'sub_dealer_id',
        'branch_id',
        'name',
        'email',
        'phone',
        'avatar',
        'google_id',
        'password',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'string',
    ];

    public function getUsernameAttribute()
    {
        return $this->name;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    public function isVerifiedTrader(): bool
    {
        $trades = \App\Models\Exchange::where(function ($q) {
            $q->where('requester_id', $this->id)->orWhere('responder_id', $this->id);
        })->where('status', 'accepted')->count();

        $rating = $this->reviewsReceived()->avg('rating') ?? 0;

        return $trades >= 10 && $rating >= 4.5;
    }

    public function completedTradesCount(): int
    {
        return \App\Models\Exchange::where(function ($q) {
            $q->where('requester_id', $this->id)->orWhere('responder_id', $this->id);
        })->where('status', 'accepted')->count();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reviewsReceived()
    {
        return $this->hasMany(UserReview::class, 'user_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(UserReview::class, 'reviewer_id');
    }
}