<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Lost extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'losts';

    protected $fillable = [
        'en_lost','ar_lost','issue_id'
    ];

    protected $hidden = [
        'issue_id'
    ];




}
