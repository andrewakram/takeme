<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OrderProduct extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'order_products';

    protected $fillable = [
        'order_id','product_id','quantity','description'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];
    protected $appends = ['product_name','product_image','price_after'];

    public function getProductNameAttribute()
    {
        return $this->product()->first()->name;
    }
    public function getProductImageAttribute()
    {
        return $this->product()->first()->image;
    }
    public function getPriceAfterAttribute()
    {
        return $this->product()->first()->price_after;
    }
    /////

    public function order_product_variations()
    {
        return $this->hasMany(OrderProductVariation::class, 'order_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

//    public function order()
//    {
//        return $this->belongsTo(Order::class, 'user_id');
//    }
//
//    function getCreatedAtAttribute()
//    {
//        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
//    }
//
//    public function getAcceptAttribute($value)
//    {
//        //return  Carbon::parse($this->attributes['accept'])->format('D,d M ,h:m a');
//        if($value)
//            return Carbon::parse($this->attributes['accept'])->translatedFormat('l jS F, h:m a');
//        else
//            return "";
//    }
//
//    public function getOnWayAttribute($value)
//    {
//        //return Carbon::parse($this->attributes['on_way'])->format('D,d M ,h:m a');
//        if($value)
//            return Carbon::parse($this->attributes['on_way'])->translatedFormat('l jS F, h:m a');
//        else
//            return "";
//    }
//
//    public function getFinishedAttribute($value)
//    {
//        if($value)
//            return Carbon::parse($this->attributes['finished'])->translatedFormat('l jS F, h:m a');
//        else
//            return "";
//    }

}
