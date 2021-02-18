<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\CartProduct;

class Cart extends Model
{
    protected $fillable = ['user_id','notes',];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class,'shop_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function productVariations()
    {
        return $this->hasManyThrough(CartProductVariation::class, CartProduct::class);
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->toDateString();
    }
}
