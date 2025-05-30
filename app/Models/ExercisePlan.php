<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExercisePlan extends Model
{
    protected $table = 'planned_exercises'; // table name

    protected $primaryKey = 'PlannedExerciseID'; // primary key

    // disable timestamps
    public $timestamps = false;

    protected $dates = ['ExerciseDateTime', 'created_at'];

    protected $fillable = [
        'UserID',
        'ExerciseDateTime',
        'ExerciseType',
        'ExerciseIntensity',
        'DurationMinutes',
        'Notes',
    ];

    protected $casts = [
        'ExerciseDateTime' => 'datetime',
        'created_at' => 'datetime',
    ];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    public function exerciseLogs()
    {
        return $this->hasOne(ExerciseLog::class, 'PlannedExerciseID', 'PlannedExerciseID');
        //                                                ^ foreign key in logged_exercises
        //                                                                   ^ local key in planned_exercises
    }
}
