<?php
// Create app/Models/ReplyLike.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'forum_reply_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function forumReply()
    {
        return $this->belongsTo(ForumReply::class, 'forum_reply_id', 'ReplyID');
        //                                      ^ foreign key in 'reply_likes' table
        //                                                       ^ primary key in 'forum_replies' table
    }
}