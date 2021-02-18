<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MessageImage extends Authenticatable
{
    use Notifiable;
    //use HasRoles;

    protected $table = 'messages_images';
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
        $value->move(public_path('/uploads/messages_images/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/uploads/messages_images/'.$value);
        }else{
            return "";
        }
    }
}
