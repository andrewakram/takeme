<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Variation;
use App\Models\Option;

class CartProductVariation extends Model
{

    protected $fillable = ['cart_item_id','order_item_id','variation_id','option_id'];

    protected $hidden = ['created_at','updated_at'];

    public function CartItem()
    {
        return $this->belongsTo(CartProduct::class,'cart_product_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class,'variation_id');
    }
    public function option()
    {
        return $this->belongsTo(Option::class,'option_id');
    }
}
