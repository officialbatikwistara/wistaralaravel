<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_produk',
        'order_id',
        'rating',
        'comment',
        'photos',
        'video',
        'status',
        'reply',
        'replied_at',
        'is_verified_purchase',
        'helpful_count'
    ];

    protected $casts = [
        'photos' => 'array',
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
        'helpful_count' => 'integer',
        'replied_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function helpfulVotes()
    {
        return $this->hasMany(ReviewHelpfulVote::class);
    }

    public function hasUserVotedHelpful($userId)
    {
        return $this->helpfulVotes()->where('user_id', $userId)->exists();
    }
}

