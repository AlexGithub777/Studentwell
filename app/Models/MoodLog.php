<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodLog extends Model
{
    protected $table = 'mood_logs'; // table name

    protected $primaryKey = 'MoodLogID'; // primary key

    // ensure the primary key is an integer
    protected $keyType = 'int';

    // ensure the primary key is incrementing
    public $incrementing = true;

    // disable timestamps
    public $timestamps = false;

    protected $dates = ['created_at'];

    // Casts
    protected $casts = [
        'MoodDate' => 'date',
    ];

    // fillable fields
    protected $fillable = [
        'UserID',
        'MoodDate',
        'MoodRating',
        'Emotions',
        'Reflection',
    ];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
        //                                                ^ foreign key in mood_logs
        //                                                                   ^ local key in users
    }
}
