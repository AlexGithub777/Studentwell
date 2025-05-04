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
    

    protected $table = 'Forum_Posts';
    protected $primaryKey = 'ForumPostID';

    protected $fillable = [
        'UserID', // This still uses UserID in the Forum_Posts table
        'PostTitle',
        'PostCategory',
        'Content',
        'PostLikes',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'PostID', 'ForumPostID');
    }
}