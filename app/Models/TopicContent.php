<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicContent extends Model
{
    //
    protected $fillable = [
        'topic_id', 'content',
    ];
}
