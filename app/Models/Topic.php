<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //
    protected $fillable = [
        'title', 'user_id', 'type', 'reading_volume',
    ];

    /**
     * 定义与文章内容关联表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function topicContent()
    {
        return $this->hasOne(TopicContent::class, 'topic_id', 'id');
    }
}
