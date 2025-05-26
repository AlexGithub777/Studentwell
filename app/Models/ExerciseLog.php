<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseLog extends Model
{
    protected $table = 'logged_exercises';

    protected $primaryKey = 'LoggedExerciseID'; // primary key3

    // disable timestamps
    public $timestamps = false;

    protected $dates = ['ExerciseDateTime', 'created_at'];

    protected $fillable = [
        'UserID',
        'PlannedExerciseID',
        'Status',
        'ExerciseDateTime',
        'ExerciseType',
        'ExerciseIntensity',
        'DurationMinutes',
        'Notes',
        'created_at',
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

    public function plannedExercise()
    {
        return $this->belongsTo(ExercisePlan::class, 'PlannedExerciseID', 'PlannedExerciseID');
        //                                                ^ foreign key in logged_exercises
        //                                                                   ^ local key in planned_exercises
    }
}
