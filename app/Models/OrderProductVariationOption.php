<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OrderProductVariationOption extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'order_product_variation_options';

    protected $fillable = [
        'order_product_var_id','option_id'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at','order_product_var_id'
    ];

    protected $appends = ['name','type','price'];

    public function getNameAttribute()
    {
        return $this->option()->first()->name;
    }
    public function getTypeAttribute()
    {
        return $this->option()->first()->type;
    }
    public function getPriceAttribute()
    {
        return $this->option()->first()->price;
    }
    /////

    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id');
    }

//    public function products()
//    {
//        return $this->hasMany(Product::class, 'menu_id');
//    }

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
