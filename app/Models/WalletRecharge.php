<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class WalletRecharge extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'wallet_recharges';

    protected $fillable = [
        'user_id','payment_id','amount'
    ];

    protected $hidden = [
        'updated_at','deleted_at',
    ];

    public function getCreatedAtAttribute()
    {
        $week=[
            'Saturday' => 'السبت',
            'Sunday' => 'الاحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الاربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
        ];
        $day_name = date('l', strtotime($this->attributes['created_at']));

        return Carbon::parse($this->attributes['created_at'])->format('Y/m/d')." ".$week["$day_name"];
    }
}
