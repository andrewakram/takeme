<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OrderProductVariation extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'order_product_variations';

    protected $fillable = [
        'order_product_id','variation_id'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    protected $appends = ['name','type','required'];

    public function getNameAttribute()
    {
        return $this->variation()->first()->name;
    }
    public function getTypeAttribute()
    {
        return $this->variation()->first()->type;
    }
    public function getRequiredAttribute()
    {
        return $this->variation()->first()->required;
    }
    /////

    public function order_product_variation_options()
    {
        return $this->hasMany(OrderProductVariationOption::class, 'order_product_var_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
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
