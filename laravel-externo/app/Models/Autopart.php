<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Autopart extends Model
{
    protected $table = 'autoparts';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image_url',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
