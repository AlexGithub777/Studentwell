<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalLog extends Model
{
    protected $table = 'goal_logs'; // table name

    protected $primaryKey = 'GoalLogID'; // primary key

    // disable timestamps
    public $timestamps = false;

    protected $dates = ['created_at'];

    // fillable fields
    protected $fillable = [
        'GoalLogID',
        'GoalID',
        'GoalLogDate',
        'GoalStatus',
        'Notes',
        'UserID',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'GoalLogDate' => 'date',
    ];

    // relationships
    public function goal()
    {
        return $this->belongsTo(Goal::class, 'GoalID', 'GoalID');
        //                                                ^ foreign key in goal_logs
        //                                                                   ^ local key in goals
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }
}
