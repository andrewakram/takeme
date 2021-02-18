<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ReplacedPoint extends Model
{
    use Notifiable;


    protected $table = 'replaced_points';

    protected $fillable = [
        'id','points', 'money','user_id','type'
    ];

    protected $hidden = [ 'deleted_at', 'updated_at',];


    public function delegate(){
        return $this->belongsTo(Delegate::class,"user_id");
    }

    function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->translatedFormat('h:m a');
//        return  Carbon::parse($this->attributes['created_at'])->isoFormat('h:mm a');
//        return Carbon::parse($this->attributes['created_at'])->format('g:i A');
    }



}
