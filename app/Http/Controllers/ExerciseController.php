<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExercisePlan;
use App\Models\ExerciseLog;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $user = auth()->user();

        // Handle filtering input
        $statusFilter = request('status'); // For logged
        $exerciseTypeFilter = request('type'); // For planned

        // Planned Exercises Filtered by ExerciseType
        $plannedExercisesQuery = $user->exercisePlans()
            ->doesntHave('exerciseLogs')
            ->with('user');

        if (!empty($exerciseTypeFilter)) {
            $plannedExercisesQuery->where('ExerciseType', $exerciseTypeFilter);
        }

        $plannedExercises = $plannedExercisesQuery
            ->paginate(5, ['*'], 'planned_exercises_page')
            ->appends([
                'planned_exercises_page' => request('planned_exercises_page'),   // Current planned page
                'logged_exercises_page' => request('logged_exercises_page'),     // Current logged page
                'type' => $exerciseTypeFilter,                                  // Planned filter
                'status' => $statusFilter                                        // Logged filter
            ]);

        // Logged Exercises Filtered by Status
        $loggedExercisesQuery = $user->exerciseLogs()->with('user');

        if (!empty($statusFilter)) {
            $loggedExercisesQuery->where('Status', $statusFilter);
        }

        $loggedExercises = $loggedExercisesQuery
            ->paginate(5, ['*'], 'logged_exercises_page')
            ->appends([
                'planned_exercises_page' => request('planned_exercises_page'),  // Current planned page
                'logged_exercises_page' => request('logged_exercises_page'),    // Current logged page
                'type' => $exerciseTypeFilter,                                  // Planned filter
                'status' => $statusFilter                                        // Logged filter
            ]);

        $allLoggedExercises = $user->exerciseLogs()->with('user')->get();

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

        // Streak calculation
        $exerciseLogsByDate = auth()->user()->exerciseLogs()
            ->orderBy('ExerciseDateTime', 'desc')
            ->pluck('ExerciseDateTime')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->unique()
            ->values()
            ->toArray();

        $streak = 0;
        $today = Carbon::now()->format('Y-m-d');

        if (!empty($exerciseLogsByDate)) {
            $mostRecentDate = $exerciseLogsByDate[0];

            // Only start counting if the most recent exercise was today or yesterday
            // This prevents counting old streaks that have been broken
            if ($mostRecentDate === $today || $mostRecentDate === Carbon::yesterday()->format('Y-m-d')) {

                // Expected next date (start from most recent exercise date)
                $expectedDate = Carbon::parse($mostRecentDate);

                // Count consecutive days
                foreach ($exerciseLogsByDate as $exerciseDate) {
                    if ($exerciseDate === $expectedDate->format('Y-m-d')) {
                        $streak++;
                        $expectedDate->subDay(); // Move to previous day
                    } else {
                        break; // Gap found, stop counting
                    }
                }
            }
        }

        $exerciseLogStreak = $streak;

        // Icons for dropdowns
        $exerciseTypes = [
            'Basketball' => '🏀',
            'Boxing' => '🥊',
            'Climbing' => '🧗',
            'Cycling' => '🚴',
            'Dance' => '💃',
            'Football' => '⚽',
            'Hiking' => '🥾',
            'Running' => '🏃',
            'Skating' => '⛸️',
            'Skiing' => '🎿',
            'Sports' => '🥇',
            'Swimming' => '🏊',
            'Tennis' => '🎾',
            'Volleyball' => '🏐',
            'Walking' => '🚶',
            'Weight Lifting' => '🏋️',
            'Yoga' => '🧘',
            'Other' => '❓',
        ];

        $exerciseTypeIcons = [
            'Basketball' => 'fa-basketball-ball',
            'Boxing' => 'fa-hand-fist',
            'Climbing' => 'fa-mountain',
            'Cycling' => 'fa-bicycle',
            'Dance' => 'fa-music',
            'Football' => 'fa-futbol',
            'Hiking' => 'fa-person-hiking',
            'Running' => 'fa-running',
            'Skating' => 'fa-person-skating',
            'Skiing' => 'fa-skiing',
            'Sports' => 'fa-medal',
            'Swimming' => 'fa-swimmer',
            'Tennis' => 'fa-tennis-ball',
            'Volleyball' => 'fa-volleyball-ball',
            'Walking' => 'fa-person-walking',
            'Weight Lifting' => 'fa-dumbbell',
            'Yoga' => 'fa-person-praying',
            'Other' => 'fa-star',
        ];

        $plannedExercises->transform(function ($exercise) use ($exerciseTypeIcons) {
            $exercise->ExerciseTypeIcon = $exerciseTypeIcons[$exercise->ExerciseType] ?? 'fa-question';
            return $exercise;
        });

        $loggedExercises->transform(function ($exercise) use ($exerciseTypeIcons) {
            $exercise->ExerciseTypeIcon = $exerciseTypeIcons[$exercise->ExerciseType] ?? 'fa-question';
            return $exercise;
        });

        // Return the view with the data
        return view('exercise.exercise', compact(
            'plannedExercises',
            'loggedExercises',
            'totalCompletedExercisesThisWeek',
            'totalMissedExercisesThisWeek',
            'totalTimeExercisedThisWeek',
            'exerciseLogStreak',
            'exerciseTypes',
            'statusFilter',
            'exerciseTypeFilter'
        ));
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
        return view('exercise.plan-exercise');
    }

    /**
     * Show the page to log a new exercise
     * 
     * @return \Illuminate\View\View
     */

    public function logExercisePage($plannedExerciseID = null)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $exercisePlan = null;
        if ($plannedExerciseID) {
            $exercisePlan = auth()->user()->exercisePlans()
                ->where('PlannedExerciseID', $plannedExerciseID)
                ->firstOrFail();

            // If the planned exercise doesn't exist, redirect back with an error
            if (!$exercisePlan) {
                return redirect()->route('exercise.index')->with('error', 'Planned exercise not found.');
            }
        }

        return view('exercise.log-exercise', [
            'exercisePlan' => $exercisePlan,
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
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // Extract the allowed exercise types from your icon map
        $allowedExerciseTypes = [
            'Basketball',
            'Boxing',
            'Climbing',
            'Cycling',
            'Dance',
            'Football',
            'Hiking',
            'Running',
            'Skating',
            'Skiing',
            'Sports',
            'Swimming',
            'Tennis',
            'Volleyball',
            'Walking',
            'Weight Lifting',
            'Yoga',
            'Other',
        ];

        $allowedIntensities = ['High Intensity', 'Moderate Intensity', 'Low Intensity'];

        $validatedData = $request->validate(
            [
                'ExerciseDateTime' => [
                    'required',
                    'date',
                    'after_or_equal:' . now()->format('Y-m-d H:i:s'),
                ],
                'ExerciseType' => [
                    'required',
                    'string',
                    'in:' . implode(',', $allowedExerciseTypes), // ✅ Enforce valid options
                ],
                'DurationMinutes' => [
                    'required',
                    'integer',
                    'min:1',
                ],
                'ExerciseIntensity' => [
                    'required',
                    'string',
                    'in:' . implode(',', $allowedIntensities), // ✅ Enforce allowed intensities
                ],
                'Notes' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ],
            [
                'ExerciseDateTime.after_or_equal' => 'The exercise date and time must be in the future.',
                'ExerciseType.in' => 'The selected exercise type is invalid. Please choose a valid option.',
                'DurationMinutes.min' => 'The duration must be at least 1 minute.',
                'Notes.max' => 'The notes may not be greater than 255 characters.',
            ]
        );

        // Create a new planned exercise
        $exercisePlan = new ExercisePlan();
        $exercisePlan->UserID = auth()->id(); // Set the UserID to the authenticated user's ID
        $exercisePlan->ExerciseDateTime = $validatedData['ExerciseDateTime'];
        $exercisePlan->ExerciseType = $validatedData['ExerciseType'];
        $exercisePlan->DurationMinutes = $validatedData['DurationMinutes'];
        $exercisePlan->ExerciseIntensity = $validatedData['ExerciseIntensity'];
        $exercisePlan->Notes = $validatedData['Notes'] ?? null; // Set notes if provided    

        $exercisePlan->save();

        return redirect()->route('exercise.index')->with('success', 'Planned exercise created successfully.');
    }

    /**
     * Store a new logged exercise
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLoggedExercise(Request $request, $plannedExerciseID = null)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        if ($plannedExerciseID) {
            // Validate the planned exercise exists
            $plannedExercise = auth()->user()->exercisePlans()
                ->where('PlannedExerciseID', $plannedExerciseID)
                ->first();

            if (!$plannedExercise) {
                return redirect()->route('exercise.log')->with('error', 'Planned exercise not found.');
            }
        } else {
            // Set Status manually if not already in request
            $request->merge(['Status' => 'Completed']);
        }

        $allowedExerciseTypes = [
            'Basketball',
            'Boxing',
            'Climbing',
            'Cycling',
            'Dance',
            'Football',
            'Hiking',
            'Running',
            'Skating',
            'Skiing',
            'Sports',
            'Swimming',
            'Tennis',
            'Volleyball',
            'Walking',
            'Weight Lifting',
            'Yoga',
            'Other',
        ];

        $allowedIntensities = ['High Intensity', 'Moderate Intensity', 'Low Intensity'];

        $rules = [
            'Status' => 'required|string|in:Completed,Missed,Partially',
            'ExerciseType' => 'required|string|in:' . implode(',', $allowedExerciseTypes),
            'DurationMinutes' => 'required|integer|min:1',
            'ExerciseIntensity' => 'required|string|in:' . implode(',', $allowedIntensities),
            'Notes' => 'nullable|string|max:255',
        ];

        // If a planned exercise ID is provided, we need to ensure the exercise date is after or equal to the planned date
        if ($plannedExerciseID) {
            // Compare against the planned exercise date
            $rules['ExerciseDateTime'] = 'required|date|after_or_equal:' . $plannedExercise->ExerciseDateTime;
        } else {
            // If no planned exercise ID is provided, just require a date and not in the future
            $rules['ExerciseDateTime'] = 'required|date|before_or_equal:now';
        }

        // Validate the request data
        $validatedData = $request->validate($rules, [
            'ExerciseDateTime.after_or_equal' => 'The exercise date and time must be after or equal to the planned date.',
            'ExerciseDateTime.before_or_equal' => 'The exercise date and time cannot be in the future.',
            'ExerciseType.in' => 'The selected exercise type is invalid.',
            'DurationMinutes.min' => 'The duration must be at least 1 minute.',
            'Notes.max' => 'Notes may not be greater than 255 characters.',
        ]);

        // Create a new exercise log
        $exerciseLog = new ExerciseLog();
        $exerciseLog->UserID = auth()->id(); // Set the UserID to the authenticated user's ID
        $exerciseLog->PlannedExerciseID = $plannedExerciseID; // Set the PlannedExerciseID if provided
        $exerciseLog->Status = $validatedData['Status'];
        $exerciseLog->ExerciseDateTime = $validatedData['ExerciseDateTime'];
        $exerciseLog->ExerciseType = $validatedData['ExerciseType'];
        $exerciseLog->DurationMinutes = $validatedData['DurationMinutes'];
        $exerciseLog->ExerciseIntensity = $validatedData['ExerciseIntensity'];
        $exerciseLog->Notes = $validatedData['Notes'] ?? null; // Set notes if provided

        // Save the exercise log
        $exerciseLog->save();

        // redirect to the exercise index page with a success message
        return redirect()->route('exercise.index')->with('success', 'Logged exercise created successfully.');
    }

    /**
     * Show the edit page for a planned exercise
     * 
     * @param int $plannedExerciseId
     * @return \Illuminate\View\View
     */
    public function editPlannedExercisePage($plannedExerciseID)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        $exercisePlan = null;
        if ($plannedExerciseID) {
            $exercisePlan = auth()->user()->exercisePlans()
                ->where('PlannedExerciseID', $plannedExerciseID)
                ->firstOrFail();

            // If the planned exercise doesn't exist, redirect back with an error
            if (!$exercisePlan) {
                return redirect()->route('exercise.index')->with('error', 'Planned exercise not found.');
            }
        }
        // Return the edit view with the planned exercise data
        return view('exercise.edit-exercise', [
            'exercisePlan' => $exercisePlan,
        ]);
    }

    /**
     * Edit a planned exercise
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePlannedExercise(Request $request, $plannedExerciseID)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // Validate the planned exercise exists
        $exercisePlan = auth()->user()->exercisePlans()
            ->where('PlannedExerciseID', $plannedExerciseID)
            ->first();
        if (!$exercisePlan) {
            return redirect()->route('exercise.index')->with('error', 'Planned exercise not found.');
        }

        // Ensure the planned exercise is not already logged
        if ($exercisePlan->exerciseLogs()->exists()) {
            return redirect()->route('exercise.index')->with('error', 'This planned exercise has already been logged and cannot be edited.');
        }

        // Extract the allowed exercise types from your icon map
        $allowedExerciseTypes = [
            'Basketball',
            'Boxing',
            'Climbing',
            'Cycling',
            'Dance',
            'Football',
            'Hiking',
            'Running',
            'Skating',
            'Skiing',
            'Sports',
            'Swimming',
            'Tennis',
            'Volleyball',
            'Walking',
            'Weight Lifting',
            'Yoga',
            'Other',
        ];

        $allowedIntensities = ['High Intensity', 'Moderate Intensity', 'Low Intensity'];

        $validatedData = $request->validate(
            [
                'ExerciseDateTime' => [
                    'required',
                    'date',
                    'after_or_equal:' . now()->format('Y-m-d H:i:s'),
                ],
                'ExerciseType' => [
                    'required',
                    'string',
                    'in:' . implode(',', $allowedExerciseTypes), // ✅ Enforce valid options
                ],
                'DurationMinutes' => [
                    'required',
                    'integer',
                    'min:1',
                ],
                'ExerciseIntensity' => [
                    'required',
                    'string',
                    'in:' . implode(',', $allowedIntensities), // ✅ Enforce allowed intensities
                ],
                'Notes' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ],
            [
                'ExerciseDateTime.after_or_equal' => 'The exercise date and time must be in the future.',
                'ExerciseType.in' => 'The selected exercise type is invalid. Please choose a valid option.',
                'DurationMinutes.min' => 'The duration must be at least 1 minute.',
                'Notes.max' => 'The notes may not be greater than 255 characters.',
            ]
        );

        // Update the planned exercise
        $exercisePlan->ExerciseDateTime = $validatedData['ExerciseDateTime'];
        $exercisePlan->ExerciseType = $validatedData['ExerciseType'];
        $exercisePlan->DurationMinutes = $validatedData['DurationMinutes'];
        $exercisePlan->ExerciseIntensity = $validatedData['ExerciseIntensity'];
        $exercisePlan->Notes = $validatedData['Notes'] ?? null; // Set notes if provided
        $exercisePlan->save();

        return redirect()->route('exercise.index')->with('success', 'Planned exercise updated successfully.');
    }
}
