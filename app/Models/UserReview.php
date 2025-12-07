<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    protected $fillable = ['reviewer_id', 'user_id', 'rating', 'comment'];

    // The person who wrote the review
    public function reviewer() {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // The person who was reviewed
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}