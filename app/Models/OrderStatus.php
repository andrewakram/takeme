<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OrderStatus extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'order_statuses';

    protected $fillable = [
        'order_id','user_id','accept','received','on_way','finished','cancelled','cancel_by','cancel_reason'
    ];

    protected $hidden = [
        'active', 'deleted_at', 'updated_at','user_id'
    ];


//    public function products()
//    {
//        return $this->hasMany(Product::class, 'menu_id');
//    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function getAcceptAttribute($value)
    {
        //return  Carbon::parse($this->attributes['accept'])->format('D,d M ,h:m a');
        if($value)
            return Carbon::parse($this->attributes['accept'])->translatedFormat('l jS F, h:m a');
        else
            return "";
    }

    public function getOnWayAttribute($value)
    {
        //return Carbon::parse($this->attributes['on_way'])->format('D,d M ,h:m a');
        if($value)
            return Carbon::parse($this->attributes['on_way'])->translatedFormat('l jS F, h:m a');
        else
            return "";
    }

    public function getReceivedAttribute($value)
    {
        //return Carbon::parse($this->attributes['on_way'])->format('D,d M ,h:m a');
        if($value)
            return Carbon::parse($this->attributes['received'])->translatedFormat('l jS F, h:m a');
        else
            return "";
    }

    public function getFinishedAttribute($value)
    {
        if($value)
            return Carbon::parse($this->attributes['finished'])->translatedFormat('l jS F, h:m a');
        else
            return "";
    }

    public function getCancelledAttribute($value)
    {
        if($value)
            return Carbon::parse($this->attributes['cancelled'])->translatedFormat('l jS F, h:m a');
        else
            return "";
    }

    public function getCancelByAttribute($value)
    {
        if($value)
            return $value;
        else
            return 0;
    }

    public function getCancelReasonAttribute($value)
    {
        if($value)
            return $value;
        else
            return "";
    }





}
