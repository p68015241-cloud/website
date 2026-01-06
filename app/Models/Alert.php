<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'chicken_id',
        'behavior',
        'video_path'
    ];
}
