<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class CancellingReason extends Model
{
    use Notifiable;
    use SoftDeletes;


    protected $table = 'cancelling_reasons';

    protected $fillable = [
        'en_reason','ar_reason','is_captin'
    ];




}
