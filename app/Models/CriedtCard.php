<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class CriedtCard extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'criedt_cards';

    protected $fillable = [
        'card_num','expire_date','cvv','name','active','user_id'
    ];




}
