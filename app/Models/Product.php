<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'price', 'stock_quantity', 'image', 'created_at'];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
