<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShopRate extends Model
{
    use Notifiable;


    protected $table = 'shops_rates';

    protected $fillable = [
        'shop_id','user_id','rate','comment','department_id'
    ];

    protected $dispatchesEvents = [
        'saved' => 'App\Events\ShopRateSavedEvent'
    ];

    protected $hidden = [
         'deleted_at', 'updated_at','shop_id','user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
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
