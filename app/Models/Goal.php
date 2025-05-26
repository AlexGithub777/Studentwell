<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $table = 'goals'; // table name

    protected $primaryKey = 'GoalID'; // primary key

    // disable timestamps
    public $timestamps = false;

    protected $dates = ['created_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'GoalStartDate' => 'date',
        'GoalTargetDate' => 'date',
    ];

    // fillable fields
    protected $fillable = [
        'GoalID',
        'UserID',
        'GoalTitle',
        'GoalCategory',
        'GoalStartDate',
        'GoalTargetDate',
        'Notes',
        'created_at',
    ];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
        //                                                ^ foreign key in goals
        //                                                                   ^ local key in users
    }

    public function goalLogs()
    {
        return $this->hasOne(GoalLog::class, 'GoalID', 'GoalID');
    }
}
