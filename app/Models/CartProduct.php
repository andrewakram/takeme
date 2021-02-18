<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    protected $fillable = ['cart_id','product_id','quantity','cost','description'];

    public function cart()
    {
        return $this->belongsTo(Cart::class,'cart_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function cartProductVariations()
    {
        return $this->hasMany(CartProductVariation::class,'cart_product_id');
    }
}
