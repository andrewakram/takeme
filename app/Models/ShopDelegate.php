<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ShopDelegate extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'shops_delegates';

    protected $fillable = [
        'shop_id','delegate_id'
    ];

//    protected $dispatchesEvents = [
//        'saved' => 'App\Events\ShopRateSavedEvent'
//    ];

    protected $hidden = [
         'deleted_at', 'updated_at','shop_id','user_id'
    ];

    public function delegate()
    {
        return $this->belongsTo(Delegate::class, 'delegate_id')
            ->select('id','name','image');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }


}
