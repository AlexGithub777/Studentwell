<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\GoalLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GoalSettingController extends Controller
{
    /**
     * Display the goals index page with key metrics and paginated data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your goals.');
        }

        $user = auth()->user();

        // Get filter inputs from request
        $goalStatusFilter = request('goal_status');        // for Goal History filter dropdown
        $goalCategoryFilter = request('goal_category');    // for Active Goals filter dropdown

        // Goals (Active Goals) query with optional category filter
        $goalsQuery = $user->goals()
            ->doesntHave('goalLogs')
            ->with('user');

        if (!empty($goalCategoryFilter)) {
            $goalsQuery->where('GoalCategory', $goalCategoryFilter);
        }

        $goals = $goalsQuery
            ->paginate(5, ['*'], 'goalsPage')
            ->appends([
                'goalLogsPage' => request('goalLogsPage'),
                'goal_status' => $goalStatusFilter,    // preserve Goal History filter on this pagination
                'goal_category' => $goalCategoryFilter // preserve this filter too
            ]);

        // Active goals count and unique categories for stats, apply same category filter
        $activeGoalsQuery = $user->goals()
            ->doesntHave('goalLogs');

        if (!empty($goalCategoryFilter)) {
            $activeGoalsQuery->where('GoalCategory', $goalCategoryFilter);
        }

        $activeGoalCount = $activeGoalsQuery->count();
        $activeGoalUniqueCategoryCount = $activeGoalsQuery->pluck('GoalCategory')->unique()->count();

        // Goal logs (Goal History) with optional status filter
        $goalLogsQuery = $user->goalLogs()
            ->with('user');

        if (!empty($goalStatusFilter)) {
            $goalLogsQuery->where('GoalStatus', $goalStatusFilter);
        }

        $goalLogs = $goalLogsQuery
            ->paginate(5, ['*'], 'goalLogsPage')
            ->appends([
                'goalsPage' => request('goalsPage'),
                'goal_status' => $goalStatusFilter,    // preserve filter
                'goal_category' => $goalCategoryFilter // preserve other filter
            ]);

        // Fetch all goal logs without pagination (filtered by status too)
        $allGoalLogs = $goalStatusFilter
            ? $user->goalLogs()->where('GoalStatus', $goalStatusFilter)->with('user')->get()
            : $user->goalLogs()->with('user')->get();

        $completedGoalsThisMonth = $allGoalLogs
            ->where('GoalStatus', 'completed')
            ->filter(fn($log) => \Carbon\Carbon::parse($log->created_at)->isCurrentMonth());

        $completedGoalCount = $completedGoalsThisMonth->count();

        $totalGoalLogsThisMonth = $allGoalLogs->filter(
            fn($log) =>
            \Carbon\Carbon::parse($log->GoalLogDate)->isCurrentMonth()
        )->count();

        $GoalCompletionRate = $totalGoalLogsThisMonth > 0
            ? round(($completedGoalCount / $totalGoalLogsThisMonth) * 100)
            : null;

        $incompleteGoalsThisMonth = $allGoalLogs
            ->where('GoalStatus', '!=', 'completed')
            ->filter(fn($log) => \Carbon\Carbon::parse($log->GoalLogDate)->isCurrentMonth());

        $incompleteGoalCount = $incompleteGoalsThisMonth->count();

        // Map active goals categories to fa-icons (add GoalCategoryIcon attribute to goals list)
        $goals->map(function ($goal) {
            $goal->GoalCategoryIcon = match ($goal->GoalCategory) {
                'Academic' => 'fa-solid fa-book',
                'Career' => 'fa-solid fa-briefcase',
                'Finance' => 'fa-solid fa-piggy-bank',
                'Hobbies' => 'fa-solid fa-palette',
                'Mental Health' => 'fa-solid fa-brain',
                'Nutrition' => 'fa-solid fa-apple-alt',
                'Physical Health' => 'fa-solid fa-heart-pulse',
                'Productivity' => 'fa-solid fa-clipboard-check',
                'Sleep' => 'fa-solid fa-bed',
                'Social' => 'fa-solid fa-users',
                'Spiritual' => 'fa-solid fa-praying-hands',
                'Travel' => 'fa-solid fa-plane',
                'Wellness' => 'fa-solid fa-heart',
                'Other' => 'fa-solid fa-circle-question',
                default => 'fa-solid fa-circle-question',
            };
            return $goal;
        });

        // Calculate goal duration (days between GoalStartDate and GoalTargetDate) for each goal log
        $goalLogs->map(function ($log) {
            if ($log->goal) {
                $log->GoalDays = \Carbon\Carbon::parse($log->goal->GoalStartDate)
                    ->diffInDays(\Carbon\Carbon::parse($log->goal->GoalTargetDate));
            } else {
                $log->GoalDays = null;
            }
            return $log;
        });

        // Pass filters to the view for dropdown selected values
        return view('goals.goals', compact(
            'activeGoalCount',
            'activeGoalUniqueCategoryCount',
            'completedGoalCount',
            'incompleteGoalCount',
            'GoalCompletionRate',
            'goalLogs',
            'goals',
            'goalStatusFilter',
            'goalCategoryFilter'
        ));
    }
    /** Show the goal setting form.
     *
     * @return \Illuminate\View\View
     * @return \Illuminate\Http\RedirectResponse
     */
    public function set()
    {
        return view('goals.set-goal');
    }

    /**
     * Store a new goal entry.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to set a goal.');
        }

        // Validate the request data
        $validatedData = $request->validate([
            'GoalTitle' => ['required', 'string', 'max:30'],
            'GoalCategory' => ['required', 'string', 'max:20'],
            'GoalStartDate' => ['required', 'date'],
            'GoalTargetDate' => ['required', 'date', 'after_or_equal:GoalStartDate'],
            'Notes' => ['nullable', 'string', 'max:255'],
        ], [
            'GoalTitle.required' => 'The goal title is required.',
            'GoalTitle.max' => 'The goal title may not be greater than 30 characters.',
            'GoalCategory.required' => 'The goal category is required.',
            'GoalStartDate.required' => 'The goal start date is required.',
            'GoalTargetDate.required' => 'The goal target date is required.',
            'Notes.max' => 'The notes may not be greater than 255 characters.',
            'GoalTargetDate.after_or_equal' => 'The target date must be a date after or equal to the start date.',
        ]);

        // Create a new goal entry
        $goal = new Goal();
        $goal->UserID = auth()->id();
        $goal->GoalTitle = $validatedData['GoalTitle'];
        $goal->GoalCategory = $validatedData['GoalCategory'];
        $goal->GoalStartDate = $validatedData['GoalStartDate'];
        $goal->GoalTargetDate = $validatedData['GoalTargetDate'];
        $goal->Notes = $validatedData['Notes'] ?? null;

        // Save the goal entry to the database
        $goal->save();

        // Redirect back to the goal setting page with a success message
        return redirect()->route('goals.index')->with('success', 'Goal added successfully.');
    }

    /**
     * Show the form for editing a goal entry.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        // Fetch the goal entry from the database
        $goal = Goal::findOrFail($id);

        // Check if the authenticated user is the owner of the goal
        if ($goal->UserID != auth()->id()) {
            return redirect()->route('goals.index')->with('error', 'Unauthorized access.');
        }

        return view('goals.edit-goal', compact('goal'));
    }

    /**
     * Update an existing goal entry.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to update a goal.');
        }

        // Validate the request data
        $validatedData = $request->validate([
            'GoalTitle' => ['required', 'string', 'max:30'],
            'GoalCategory' => ['required', 'string', 'max:20'],
            'GoalStartDate' => ['required', 'date'],
            'GoalTargetDate' => ['required', 'date', 'after_or_equal:GoalStartDate'],
            'Notes' => ['nullable', 'string', 'max:255'],
        ], [
            'GoalTitle.required' => 'The goal title is required.',
            'GoalTitle.max' => 'The goal title may not be greater than 30 characters.',
            'GoalCategory.required' => 'The goal category is required.',
            'GoalStartDate.required' => 'The goal start date is required.',
            'GoalTargetDate.required' => 'The goal target date is required.',
            'Notes.max' => 'The notes may not be greater than 255 characters.',
            'GoalTargetDate.after_or_equal' => 'The target date must be a date after or equal to the start date.',
        ]);

        // Fetch the goal entry from the database
        $goal = Goal::findOrFail($id);

        // Check if the authenticated user is the owner of the goal
        if ($goal->UserID != auth()->id()) {
            return redirect()->route('goals.index')->with('error', 'Unauthorized access.');
        }

        // Update the goal entry
        $goal->GoalTitle = $validatedData['GoalTitle'];
        $goal->GoalCategory = $validatedData['GoalCategory'];
        $goal->GoalStartDate = $validatedData['GoalStartDate'];
        $goal->GoalTargetDate = $validatedData['GoalTargetDate'];
        $goal->Notes = $validatedData['Notes'] ?? null;

        // Save the updated goal entry to the database
        $goal->save();

        // Redirect back to the goal setting page with a success message
        return redirect()->route('goals.index')->with('success', 'Goal updated successfully.');
    }


    /**
     * Show the goal logging form for a specific goal.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function log($id)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to log a goal.');
        }

        // get the goal log from "goals" table
        $goal = Goal::findOrFail($id);

        // Check if the authenticated user is the owner of the goal
        if ($goal->UserID != auth()->id()) {
            return redirect()->route('goals.index')->with('error', 'Unauthorized access.');
        }

        return view('goals.log-goal', compact('goal'));
    }

    /**
     * Store a new goal log entry.
     *
     * @param Request $request
     * @param int $goalId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLog(Request $request, $goalId)
    {
        // Fetch the goal entry once
        $goal = Goal::findOrFail($goalId);

        // Check ownership early
        if ($goal->UserID != auth()->id()) {
            return redirect()->route('goals.index')->with('error', 'Unauthorized access.');
        }

        // Prepare today's date for validation
        $today = now()->toDateString();

        // Validate inputs (including the goalId passed in URL)
        $validator = Validator::make(
            array_merge($request->all(), ['GoalID' => $goalId]),
            [
                'GoalID' => ['required', 'exists:goals,GoalID'],
                'GoalLogDate' => ['required', 'date', Rule::in([$today])],
                'GoalStatus' => ['required', 'string', 'max:15'],
                'Notes' => ['nullable', 'string', 'max:255'],
            ],
            [
                'GoalID.required' => 'The goal ID is required.',
                'GoalID.exists' => 'The selected goal does not exist.',
                'GoalLogDate.required' => 'The goal log date is required.',
                'GoalLogDate.date' => 'The goal log date must be a valid date.',
                'GoalLogDate.in' => 'You can only log a goal for today’s date.',
                'GoalStatus.required' => 'The goal status is required.',
                'Notes.max' => 'The notes may not be greater than 255 characters.',
            ]
        );

        // Add custom validation after the main check
        $validator->after(function ($validator) use ($goal, $request) {
            if ($goal->GoalTargetDate > now() && $request->input('GoalStatus') === 'completed') {
                $validator->errors()->add('GoalStatus', 'You cannot mark this goal as completed before the target date.');
            }
        });

        // Check if validation fails
        if ($validator->fails()) {
            // Redirect back with errors and input
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If validation passes, get the validated data
        $validatedData = $validator->validated();

        // Create and save goal log
        $goalLog = new GoalLog([
            'GoalID' => $validatedData['GoalID'],
            'GoalLogDate' => $validatedData['GoalLogDate'],
            'GoalStatus' => $validatedData['GoalStatus'],
            'Notes' => $validatedData['Notes'] ?? null,
            'UserID' => auth()->id(),
        ]);

        // Save the goal log entry to the database
        $goalLog->save();

        // Redirect back to the goal setting page with a success message
        return redirect()->route('goals.index')->with('success', 'Goal log added successfully.');
    }
}
