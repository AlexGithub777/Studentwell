<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
    use HasFactory;

    protected $table = 'forum_replies';
    protected $primaryKey = 'ReplyID';
    public $timestamps = false;

    // Define the date format for the created_at column
    protected $dates = ['created_at'];

    // Cast the created_at column to a datetime type
    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'UserID',
        'PostID',
        'Content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'PostID', 'ForumPostID');
    }

    public function likes()
    {
        return $this->hasMany(ReplyLike::class, 'forum_reply_id', 'ReplyID');
        //                                   ^ foreign key in 'reply_likes'
        //                                                ^ local key in 'forum_replies'
    }

    public function isLikedByUser(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
