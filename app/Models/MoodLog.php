<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodLog extends Model
{
    protected $table = 'mood_logs'; // table name

    protected $primaryKey = 'MoodLogID'; // primary key

    // disable timestamps
    public $timestamps = false;

    protected $dates = ['created_at'];


    // fillable fields
    protected $fillable = [
        'MoodLogID',
        'UserID',
        'MoodRating',
        'Emotions',
        'Reflection',
        'created_at',
    ];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
        //                                                ^ foreign key in mood_logs
        //                                                                   ^ local key in users
    }   
}
