<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthInsightsController extends Controller
{
    /**
     * Display the health insights dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // get thew data for the overview tab

        //<!-- bar graph of logs across features (mood, exercise, sleep, goals) -->
        // get count of logs for mood
        $moodLogCount = auth()->user()->moodLogs()->count();

        // get count of logs for exercise
        $exerciseLogCount = auth()->user()->exerciseLogs()->count();

        // get count of logs for sleep
        $sleepLogCount = auth()->user()->sleepLogs()->count();

        // get count of logs for goals
        $goalsLogCount = auth()->user()->goalLogs()->count();



        //<!-- donut graph of sleep consistency (daily hours of sleep) count of <6hrs, 6-8 hrs, 8+ hours)
        $sleepConsistency = [
            'less_than_6' => auth()->user()->sleepLogs()->where('SleepDurationMinutes', '<', 360)->count(),
            'between_6_and_8' => auth()->user()->sleepLogs()->whereBetween('SleepDurationMinutes', [360, 480])->count(),
            'more_than_8' => auth()->user()->sleepLogs()->where('SleepDurationMinutes', '>', 480)->count(),
        ];

        //<!-- line graph of mood over time (14 days)-->
        $moodRatings = auth()->user()->moodLogs()
            ->where('MoodDate', '>=', now()->subDays(14))
            ->orderBy('MoodDate')
            ->get(['MoodDate', 'MoodRating']);

        //<!-- pie chart of goals by category (exercise, sleep, mood) -->
        $goalsByCategory = auth()->user()->goals()
            ->selectRaw('GoalCategory, COUNT(*) as count')
            ->groupBy('GoalCategory')
            ->get();



        return view('health-insights.health-insights', [
            'currentTab' => 'overview',
            'moodLogCount' => $moodLogCount,
            'exerciseLogCount' => $exerciseLogCount,
            'sleepLogCount' => $sleepLogCount,
            'goalsLogCount' => $goalsLogCount,
            'sleepConsistency' => $sleepConsistency,
            'moodRatings' => $moodRatings,
            'goalsByCategory' => $goalsByCategory,
        ]);
    }

    public function mood()
    {
        return view('health-insights.health-insights', ['currentTab' => 'mood']);
    }

    public function exercise()
    {
        return view('health-insights.health-insights', ['currentTab' => 'exercise']);
    }

    public function sleep()
    {
        return view('health-insights.health-insights', ['currentTab' => 'sleep']);
    }

    public function goals()
    {
        return view('health-insights.health-insights', ['currentTab' => 'goals']);
    }
}
