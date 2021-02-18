<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Term extends Model
{
    use Notifiable;


    protected $table = 'terms';

    protected $fillable = [
        'id','term_ar', 'term_en'
    ];




}
