<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OrderOffer extends Model
{
    use Notifiable;
    //use SoftDeletes;


    protected $table = 'order_offers';

    protected $fillable = [
        'order_id','delegate_id','offer','status','distance'
    ];


    protected $hidden = [
        'active', 'deleted_at', 'updated_at','user_id'
    ];

    public function delegate()
    {
        return $this->belongsTo(Delegate::class, 'delegate_id')
            ->select('id','f_name','l_name','image','rate','token');
    }



//    public function order()
//    {
//        return $this->belongsTo(Order::class, 'user_id');
//    }
//

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
