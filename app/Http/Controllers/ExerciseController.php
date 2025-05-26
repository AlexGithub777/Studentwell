<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExercisePlan;
use App\Models\ExerciseLog;
use Carbon\Carbon;

class ExerciseController extends Controller
{
    /**
     * Display the exercise planning and logging page with data
     * 
     * @return \Illuminate\View\View
     * 
     */
    public function index()
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // Get the authenticated user
        $user = auth()->user();

        // Fetch the planned exercises and logged exercises for the 
        $plannedExercises = $user->exercisePlans()
            ->doesntHave('exerciseLogs') // Only get planned exercises that have no logs
            ->with('user')
            ->paginate(5, ['*'], 'planned_exercises_page')
            ->appends([
                'logged_exercises_page' => request('logged_exercises_page')
            ]);

        //get unpaginated logged exercises
        // Fetch the logged exercises for the user
        $allLoggedExercises = $user->exerciseLogs()
            ->with('user')
            ->get();

        $loggedExercises = $user->exerciseLogs()
            ->with('user')
            ->paginate(5, ['*'], 'logged_exercises_page')
            ->appends([
                'planned_exercises_page' => request('planned_exercises_page')
            ]);

        // add 4 key metrics

        // Total Completed Exercises this week
        $totalCompletedExercisesThisWeek = $allLoggedExercises->filter(function ($log) {
            return $log->Status === 'Completed' && Carbon::parse($log->ExerciseDateTime)->isCurrentWeek();
        })->count();

        // Total Missed Exercises this week
        $totalMissedExercisesThisWeek = $allLoggedExercises->filter(function ($log) {
            return $log->Status === 'Missed' && Carbon::parse($log->ExerciseDateTime)->isCurrentWeek();
        })->count();

        // Total time exercised this week
        $totalTimeExercisedThisWeek = $allLoggedExercises->filter(function ($log) {
            return Carbon::parse($log->ExerciseDateTime)->isCurrentWeek();
        })->sum('DurationMinutes');

        // streak calculation
        $exerciseLogsByDate = auth()->user()->exerciseLogs()
            ->orderBy('ExerciseDateTime', 'desc')
            ->pluck('ExerciseDateTime')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->unique()
            ->values();

        $streak = 0;

        //get the current date
        $currentDate = Carbon::now()->format('Y-m-d');

        // Loop through the dates to calculate the streak
        foreach ($exerciseLogsByDate as $date) {
            if (Carbon::parse($date)->isToday()) {
                $streak++;
            } elseif (Carbon::parse($date)->isYesterday()) {
                $streak++;
            } elseif (Carbon::parse($date)->isSameDay(Carbon::now()->subDays(2))) {
                $streak++;
            } else {
                break; // Stop counting streak if a gap is found
            }
        }

        $exerciseLogStreak = $streak;

        /// Map the exercise types to fa icons
        $exerciseTypeIcons = [
            'Running'       => 'fa-running',
            'Walking'       => 'fa-person-walking',
            'Cycling'       => 'fa-bicycle',
            'Swimming'      => 'fa-swimmer',
            'Yoga'          => 'fa-person-praying',
            'Weightlifting' => 'fa-dumbbell',
            'Weight Lifting' => 'fa-dumbbell',
            'Dance'         => 'fa-music',
            'Basketball'    => 'fa-basketball-ball',
            'Football'      => 'fa-football-ball',
            'Volleyball'    => 'fa-volleyball-ball',
            'Sports'        => 'fa-medal',
            'Other'         => 'fa-star'
        ];

        // add ExerciseTypeIcons to $plannedExercises and $loggedExercises
        $plannedExercises->transform(function ($exercise) use ($exerciseTypeIcons) {
            $exercise->ExerciseTypeIcon = $exerciseTypeIcons[$exercise->ExerciseType] ?? 'fa-question';
            return $exercise;
        });

        $loggedExercises->transform(function ($exercise) use ($exerciseTypeIcons) {
            $exercise->ExerciseTypeIcon = $exerciseTypeIcons[$exercise->ExerciseType] ?? 'fa-question';
            return $exercise;
        });

        // Return the view with the data
        return view(
            'exercise.exercise',
            compact(
                'plannedExercises',
                'loggedExercises',
                'totalCompletedExercisesThisWeek',
                'totalMissedExercisesThisWeek',
                'totalTimeExercisedThisWeek',
                'exerciseLogStreak',
            )
        );
    }

    /**
     * Show the page to add a new planned exercise
     * 
     * @return \Illuminate\View\View
     */
    public function addPlannedExercisePage()
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // Return the view for adding a planned exercise
        return view('exercise.add-exercise');
    }

    /**
     * Show the page to log a new exercise
     * 
     * @return \Illuminate\View\View
     */

    public function logExercisePage()
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // Fetch all planned exercises for the user
        $plannedExercises = auth()->user()->exercisePlans()
            ->doesntHave('exerciseLogs') // Only get planned exercises that have no logs
            ->get();

        // Return the view for logging an exercise with the planned exercises
        return view('exercise.log-exercise', [
            'plannedExercises' => $plannedExercises,
        ]);
    }

    /**
     * Store a new planned exercise
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePlannedExercise(Request $request)
    {
        // Validate the request data
        $request->validate([
            'exercise_type' => 'required|string|max:255',
            'exercise_intensity' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Create a new planned exercise
        $plannedExercise = auth()->user()->exercisePlans()->create([
            'ExerciseType' => $request->input('exercise_type'),
            'ExerciseIntensity' => $request->input('exercise_intensity'),
            'DurationMinutes' => $request->input('duration_minutes'),
            'Notes' => $request->input('notes'),
        ]);

        return redirect()->route('exercise.index')->with('success', 'Planned exercise created successfully.');
    }

    /**
     * Store a new logged exercise
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLoggedExercise(Request $request)
    {
        // Validate the request data
        $request->validate([
            'planned_exercise_id' => 'required|exists:planned_exercises,PlannedExerciseID',
            'exercise_date_time' => 'required|date',
            'exercise_type' => 'required|string|max:255',
            'exercise_intensity' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Create a new logged exercise
        auth()->user()->exerciseLogs()->create([
            'PlannedExerciseID' => $request->input('planned_exercise_id'),
            'ExerciseDateTime' => $request->input('exercise_date_time'),
            'ExerciseType' => $request->input('exercise_type'),
            'ExerciseIntensity' => $request->input('exercise_intensity'),
            'DurationMinutes' => $request->input('duration_minutes'),
            'Notes' => $request->input('notes'),
        ]);

        return redirect()->route('exercise.index')->with('success', 'Logged exercise created successfully.');
    }

    /**
     * Show the edit page for a planned exercise
     * 
     * @param int $plannedExerciseId
     * @return \Illuminate\View\View
     */
    public function editPlannedExercisePage($plannedExerciseId)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // Find the planned exercise
        $plannedExercise = auth()->user()->exercisePlans()->findOrFail($plannedExerciseId);

        // Return the edit view with the planned exercise data
        return view('exercise.edit-exercise', [
            'plannedExercise' => $plannedExercise,
        ]);
    }

    /**
     * Edit a planned exercise
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editPlannedExercise(Request $request)
    {
        // Validate the request data
        $request->validate([
            'planned_exercise_id' => 'required|exists:planned_exercises,PlannedExerciseID',
            'exercise_type' => 'required|string|max:255',
            'exercise_intensity' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Find the planned exercise
        $plannedExercise = auth()->user()->exercisePlans()->findOrFail($request->input('planned_exercise_id'));

        // Update the planned exercise
        $plannedExercise->update([
            'ExerciseType' => $request->input('exercise_type'),
            'ExerciseIntensity' => $request->input('exercise_intensity'),
            'DurationMinutes' => $request->input('duration_minutes'),
            'Notes' => $request->input('notes'),
        ]);

        return redirect()->route('exercise.index')->with('success', 'Planned exercise updated successfully.');
    }
}
