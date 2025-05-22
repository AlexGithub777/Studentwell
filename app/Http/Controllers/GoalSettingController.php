<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;

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
    public function storeLog(Request $request)
    {
        // Validate the request data
        $request->validate([
            'GoalID' => 'required|exists:goals,GoalID',
            'GoalLogDate' => 'required|date',
            'GoalStatus' => 'required|string|max:15',
            'Notes' => 'required|string|max:255',
        ]);

        // Create a new goal log entry
        $goalLog = new GoalLog();
        $goalLog->GoalID = $request->input('GoalID');
        $goalLog->GoalLogDate = $request->input('GoalLogDate');                                                         
        $goalLog->GoalStatus = $request->input('GoalStatus');
        $goalLog->Notes = $request->input('Notes');

        // Save the goal log entry to the database
        $goalLog->save();

        // Redirect back to the goal setting page with a success message
        return redirect()->route('goals.index')->with('success', 'Goal log added successfully.');
    }
}
