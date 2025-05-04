<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
    use HasFactory;

    protected $table = 'Forum_Replies';
    protected $primaryKey = 'ReplyID'; // Make sure this matches your updated schema

    protected $fillable = [
        'UserID',
        'PostID',
        'Content',
        'ReplyLikes'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'PostID', 'ForumPostID');
    }
}