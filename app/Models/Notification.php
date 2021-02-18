<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Notification extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'notifications';
    protected $fillable = ['title','body','user_id','order_id','department_id','type','image'];
//type =>>>0=>user, 1=>delegate, 2=>driver, 3=>all

    function getCreatedAtAttribute()
    {
        //return  Carbon::parse($this->attributes['created_at'])->diffForHumans();
        return Carbon::parse($this->attributes['created_at'])->format('d M yy g:i A');
    }

    public function setImageAttribute($value)
    {
        if($value) {
            $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
            $value->move(public_path('/uploads/notifications/'),$img_name);
            $this->attributes['image'] = $img_name ;
        }
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/notifications/'.$value);
        }else{
            return asset('/default.png');
        }
    }



    public static function send($tokens, $title="hello", $msg="helo msg", $type=1,
                                $is_captin=0,$new_offer=null,$chat=null,$wallet=null,
                                $order_id=null,$confirm_accept_order =0,$trip=null,
                                $uber_chat=null){
//type >>>> 0=>new order , get lower offer  >notifications go for delegate
//type >>>> 1=>change status , chat     >notifications go for user & delegate
//type >>>> 3=>user accept offer    >notifications go for delegate

//type >>>> 0=>new offer    >notifications go for user
//type >>>> 4=>chat with admin    >notifications go for user

//type >>>> 5 for user uber chat

        $key = 'AAAABR9KiQs:APA91bELgVv_U-wWQqnFs6G3k2VelotsqL1YCr2MZR_vUZPfNVvIt_lLUaQdYy1qklBW86XX2xGtrVKKLTl3_PB4ph6buW7tEqIf9U7zc6dgnDkCCrfgcuDL9Oj7UXuuI_u4w69QtSxU';

        $fields = array
        (
            "registration_ids" => (array)$tokens,
            "priority" => 10,
            'data' => [
                'title' => $title,
                'body' => $msg,
                'new_offer' => $new_offer,
                'chat' => $chat,
                'uber_chat' => $uber_chat,
                'wallet' => $wallet,
                'type' => $type,
                'order_id' => $order_id,
                'trip_details' => $trip,
                'confirm_accept_order' => $confirm_accept_order,
                'icon' => 'myIcon',
                'sound' => 'mySound'
            ]
        ,
//            'notification' => [
//                'title' => $title,
//                'body' => $msg,
//                'new_offer' => $new_offer,
//                'chat' => $chat,
//                'wallet' => $wallet,
//                'type' => $type,
//                'order_id' => $order_id,
//                'trip_details' => $trip,
//                'confirm_accept_order' => $confirm_accept_order,
//                'icon' => 'myIcon',
//                'sound' => 'mySound'
//            ],
            'vibrate' => 1,
            'sound' => 1
        );

        $headers = array
        (
            'accept: application/json',
            'Content-Type: application/json',
            'Authorization: key=' . $key
        );
        //////
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // $result = curl_exec($ch);
        // curl_close($ch);
        // return $result;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
//        dd($result);
        //  var_dump($result);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        //dd($result);
        return $result;
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function delegate(){
        return $this->belongsTo(Delegate::class,'user_id');
    }

    public function driver(){
        return $this->belongsTo(Driver::class,'user_id');
    }

}
