<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserAdminMessage extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'user_admin_messages';
//sender_type: 0=>user , 1=>admin
    protected $fillable = [
        'user_id','message','sender_type'
    ];

    protected $hidden = [
        'updated_at','deleted_at',
    ];

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d F Y H:i A');
    }

    public function getMessageAttribute($value)
    {
        if($value)
            return $value;
        return "";
    }

    public function user_admin_messages_image(){
        return $this->hasOne(UserAdminMessageImage::class,"message_id");
    }

}
