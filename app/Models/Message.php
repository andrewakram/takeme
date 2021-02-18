<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Message extends Authenticatable
{
    use Notifiable;
    //use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id', 'sender_type', 'receiver_id','receiver_type',
        'message','type','order_id','confirm_accept_order'
    ];
    protected $hidden = [
        'updated_at','deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->select('id','name','phone','lat','lng','image');
    }

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

    public function messages_image(){
        return $this->hasOne(MessageImage::class,"message_id");
    }






}
