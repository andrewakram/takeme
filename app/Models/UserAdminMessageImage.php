<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserAdminMessageImage extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'user_admin_messages_images';
//sender_type: 0=>user , 1=>admin
    protected $fillable = [
        'message_id','image'
    ];

    protected $hidden = [
        'created_at','updated_at','deleted_at','message_id'
    ];

//    public function getCreatedAtAttribute()
//    {
//        return Carbon::parse($this->attributes['created_at'])->format('d F Y H:i A');
//    }

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('/uploads/user_admin_messages_images/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/user_admin_messages_images/'.$value);
        }else{
            return "";
        }
    }

}
