<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\GoalLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GoalSettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Calculate key metrics for goals

        $goals = $user->goals()
            ->doesntHave('goalLogs')
            ->with('user')
            ->paginate(5, ['*'], 'goalsPage')
            ->appends(['goalLogsPage' => request('goalLogsPage')]);

        // Active goals
        $activeGoals = $user->goals()
            ->with('user')
            ->doesntHave('goalLogs');


        $activeGoalCount = $activeGoals->count();
        $activeGoalUniqueCategoryCount = $activeGoals->pluck('GoalCategory')->unique()->count();

        // Goal logs for this month
        $goalLogs = $user->goalLogs()
            ->with('user')
            ->paginate(5, ['*'], 'goalLogsPage')
            ->appends(['goalsPage' => request('goalsPage')]);

        // fetch all goals logs wihout pagination
        $allGoalLogs = $user->goalLogs()
            ->with('user')
            ->get();


        $completedGoalsThisMonth = $allGoalLogs
            ->where('GoalStatus', 'completed')
            ->filter(function ($log) {
                return \Carbon\Carbon::parse($log->created_at)->isCurrentMonth();
            });

        $completedGoalCount = $completedGoalsThisMonth->count();
        $totalGoalLogsThisMonth = $allGoalLogs->filter(function ($log) {
            return \Carbon\Carbon::parse($log->GoalLogDate)->isCurrentMonth();
        })->count();

        // Calculate goal completion rate
        $GoalCompletionRate = $totalGoalLogsThisMonth > 0
            ? round(($completedGoalCount / $totalGoalLogsThisMonth) * 100)
            : null;

        // Calculate number of incomplete goals this month
        $incompleteGoalsThisMonth = $allGoalLogs
            ->where('GoalStatus', '!=', 'completed')
            ->filter(function ($log) {
                return \Carbon\Carbon::parse($log->GoalLogDate)->isCurrentMonth();
            });
        $incompleteGoalCount = $incompleteGoalsThisMonth->count();


        // map active goals categories to fa-icons (add GoalCategoryIcon attribute to goals list)
        $goals->map(function ($goal) {
            $goal->GoalCategoryIcon = match ($goal->GoalCategory) {
                'Academic' => 'fa-solid fa-book',
                'Career' => 'fa-solid fa-briefcase',
                'Finance' => 'fa-solid fa-piggy-bank',
                'Hobbies' => 'fa-solid fa-palette',
                'Mental Health' => 'fa-solid fa-brain',
                'Nutrition' => 'fa-solid fa-apple-alt',
                'Physical Health' => 'fa-solid fa-dumbell',
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
                $log->GoalDays = null; // Or 0, or fallback if goal is missing
            }
            return $log;
        });

        return view('goals.goals', compact(
            'activeGoalCount',
            'activeGoalUniqueCategoryCount',
            'completedGoalCount',
            'incompleteGoalCount',
            'GoalCompletionRate',
            'goalLogs',
            'goals'
        ));
    }


    // show the goal setting form
    public function set()
    {
        return view('goals.set-goal');
    }

    // store the goal setting form
    public function store(Request $request)
    {
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

    // show the goal editing form
    public function edit($id)
    {
        // Fetch the goal entry from the database
        $goal = Goal::findOrFail($id);

        // Check if the authenticated user is the owner of the goal
        if ($goal->UserID !== auth()->id()) {
            return redirect()->route('goals.index')->with('error', 'Unauthorized access.');
        }

        return view('goals.edit-goal', compact('goal'));
    }

    // update the goal entry
    public function update(Request $request, $id)
    {
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
        if ($goal->UserID !== auth()->id()) {
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


    // show the goal logging form
    public function log($id)
    {
        // get the goal log from "goals" table
        $goal = Goal::findOrFail($id);

        // Check if the authenticated user is the owner of the goal
        if ($goal->UserID !== auth()->id()) {
            return redirect()->route('goals.index')->with('error', 'Unauthorized access.');
        }

        return view('goals.log-goal', compact('goal'));
    }

    // store the goal logging form
    public function storeLog(Request $request, $goalId)
    {
        // Fetch the goal entry once
        $goal = Goal::findOrFail($goalId);

        // Check ownership early
        if ($goal->UserID !== auth()->id()) {
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

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

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
