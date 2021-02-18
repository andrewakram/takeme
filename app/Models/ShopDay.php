<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShopDay extends Model
{
    use Notifiable;


    protected $table = 'shops_days';

    protected $fillable = [
        'shop_id','day_id','from','to'
    ];

    protected $hidden = [
         'deleted_at','created_at', 'updated_at','shop_id','user_id'
    ];

    protected $appends = ['day','name_en'];

    public function shop()
    {
        return $this->belongsTo(shop::class,"shop_id");
    }

    public function day()
    {
        return $this->belongsTo(Day::class,"day_id");
    }

    public function getDayAttribute()
    {
        $data = $this->day()->first()->name;
        //unset($this->day);
        return $data;
    }

//    public function getIsOpenAttribute()
//    {
//        $data = $this->getNameEnAttribute() == Carbon::now()->format('l') ? $this->is_open = true : $this->is_open= false;
//        //unset($this->day);
//        return $data;
//    }

    public function getNameEnAttribute()
    {
        return $this->day()->first()->name_en;
    }

    public function getTodayAttribute()
    {
        $data = $this->getNameEnAttribute() == Carbon::now()->format('l') ? $this->today = true : $this->today= false;

        return $data;
        //$dayOfWeek = date("l", strtotime($_GET['b_date']));
    }

    public function getFromAttribute()
    {
        return  Carbon::parse($this->attributes['from'])->isoFormat('h:mm a');
    }

    public function getToAttribute()
    {
        return  Carbon::parse($this->attributes['to'])->isoFormat('h:mm a');
    }




}
