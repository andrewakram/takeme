<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Rushhour extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'rushhours';

    protected $fillable = [
        'from', 'to', 'country_id'
    ];



}
