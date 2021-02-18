<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class BankingTransfer extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'banking_transfers';

    protected $fillable = [
        'bank_name','transfer_no','transfer_value','image','user_id','bank_account_id'
    ];

    public function setImageAttribute($value)
    {
        $img_name = time().uniqid().'.'.$value->getClientOriginalExtension();
        $value->move(public_path('banking_transfers/'),$img_name);
        $this->attributes['image'] = $img_name ;
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('/banking_transfers/'.$value);
        }else{
            return asset('/default.png');
        }
    }

    public function getTransferImageAttribute($value)
    {
        if($value)
        {
            return asset('/banking_transfers/'.$value);
        }else{
            return asset('/default.png');
        }
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d F Y , g:i A');
    }
    public function getTransferNoAttribute($value)
    {
        if($value)
        {
            return "$value";
        }else{
            "";
        }
    }

    public function getTransferValueAttribute($value)
    {
        if($value)
        {
            return "$value";
        }else{
            "";
        }
    }


}
