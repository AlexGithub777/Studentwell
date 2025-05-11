<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    // disbale timestamps
    public $timestamps = false;

    // add created_at and updated_at columns
    protected $dates = ['created_at', 'updated_at'];
    // add created_at and updated_at columns set type to datetime
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    

    protected $table = 'forum_posts';
    protected $primaryKey = 'ForumPostID';

    protected $fillable = [
        'UserID', // This still uses UserID in the Forum_Posts table
        'ForumPostID', // This is the primary key
        'PostTitle',
        'PostCategory',
        'Content',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
        //                               ^ foreign key in forum_posts table
        //                                         ^ local key in users table (likely 'id')
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'PostID', 'ForumPostID');
        //                                     ^ foreign key in 'forum_replies'
        //                                                      ^ local key in 'forum_posts'
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'forum_post_id', 'ForumPostID');
        //                                 ^ foreign key in 'likes'
        //                                              ^ local key in 'forum_posts'
    }

    public function isLikedByUser(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}