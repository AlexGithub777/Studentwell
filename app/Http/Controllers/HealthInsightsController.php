<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HealthInsightsController extends Controller
{
    /**
     * Display the health insights dashboard.
     *
     * @return \Illuminate\View\View
     * @param \Illuminate\Http\Request $request
     */
    public function index(Request $request)
    {
        $currentTab = $request->query('tab', 'overview');
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

        // line graph of mood over time (30 days)
        $moodRatings30days = auth()->user()->moodLogs()
            ->where('MoodDate', '>=', now()->subDays(30))
            ->orderBy('MoodDate')
            ->get(['MoodDate', 'MoodRating', 'Emotions']);

        // mood distribution for the last 30 days (pie chart, mood raings)
        $moodDistribution = auth()->user()->moodLogs()
            ->where('MoodDate', '>=', now()->subDays(30))
            ->selectRaw('MoodRating, COUNT(*) as count')
            ->groupBy('MoodRating')
            ->get();

        $totalDays = 31;

        $loggedDays = auth()->user()->moodLogs()
            ->whereDate('MoodDate', '>=', now()->subDays(30)->toDateString())
            ->distinct(DB::raw('DATE(MoodDate)')) // Better for timestamp precision
            ->count();

        $moodLoggingRate = $totalDays > 0 ? ($loggedDays / $totalDays) * 100 : 0;
        $moodLoggingRate = round(min(max($moodLoggingRate, 0), 100), 1);


        $emotionCounts = [];

        foreach ($moodRatings30days as $log) {
            $emotions = json_decode($log->Emotions, true);
            if (is_array($emotions)) {
                foreach ($emotions as $emotion) {
                    $emotionCounts[$emotion] = ($emotionCounts[$emotion] ?? 0) + 1;
                }
            }
        }

        $emotionsDistribution = collect($emotionCounts)->map(function ($count, $emotion) {
            return ['emotion' => $emotion, 'count' => $count];
        })->values();

        $top = collect($emotionCounts)->sortDesc()->take(10);
        // Remove these two lines to skip "Other" completely
        // $otherTotal = collect($emotionCounts)->sortDesc()->skip(10)->sum();

        // Use only the top 10 without "Other"
        $emotionsDistribution = $top->map(function ($count, $emotion) {
            return ['emotion' => $emotion, 'count' => $count];
        })->values();
        // Exercise insights

        // <!-- line graph of exercise duration over time (30 days) -->
        $exerciseLogs = auth()->user()->exerciseLogs()
            ->where('ExerciseDateTime', '>=', now()->subDays(30))
            ->orderBy('ExerciseDateTime')
            ->get(['ExerciseDateTime', 'DurationMinutes', 'ExerciseType']);

        //<!-- bar graph of weekly exercise duration (last 4 weeks) -->
        // Fetch weekly sums for last 4 weeks
        $exerciseLogsLast4Weeks = auth()->user()->exerciseLogs()
            ->selectRaw('YEAR(ExerciseDateTime) as year, WEEK(ExerciseDateTime, 1) as week, SUM(DurationMinutes) as total_duration')
            ->where('ExerciseDateTime', '>=', now()->subDays(28))
            ->groupBy('year', 'week')
            ->orderBy('year')
            ->orderBy('week')
            ->get();

        // Map weeks to their start dates (Monday)
        $weekLabels = $exerciseLogsLast4Weeks->map(function ($item) {
            // Carbon::now()->setISODate(year, week) gives Monday of that week
            $startOfWeek = Carbon::now()->setISODate($item->year, $item->week)->format('M d, Y');
            return $startOfWeek;
        })->toArray();

        $durations = $exerciseLogsLast4Weeks->pluck('total_duration')->toArray();


        //<!-- donut chart of exercise completion rate (if exercise is Completed, Missed or Partially)
        // Get counts of Completed, Missed, Partially
        $exerciseStatusCounts = auth()->user()->exerciseLogs()
            ->select('Status', \DB::raw('COUNT(*) as count'))
            ->groupBy('Status')
            ->pluck('count', 'Status');

        $totalExercises = $exerciseStatusCounts->sum();

        $exerciseChartData = $exerciseStatusCounts->map(function ($count, $status) use ($totalExercises) {
            $percentage = $totalExercises > 0 ? round(($count / $totalExercises) * 100, 1) : 0;
            return [
                'status' => $status,
                'count' => $count,
                'percentage' => $percentage,
            ];
        })->values()->toArray();

        // Now you have:
        // $exerciseStatusCounts = ['Completed' => n1, 'Missed' => n2, 'Partially' => n3]
        // $exerciseCompletionRate = percentage of Completed exercises

        // <!-- pie chart of exercise types distribution -->
        $exerciseTypesDistribution = $exerciseLogs
            ->groupBy('ExerciseType')
            ->map(function ($group) {
                return $group->count();
            });

        // Sleep insights

        // Goals insights



        return view('health-insights.health-insights', [
            'currentTab' => $currentTab,
            'moodLogCount' => $moodLogCount,
            'exerciseLogCount' => $exerciseLogCount,
            'sleepLogCount' => $sleepLogCount,
            'goalsLogCount' => $goalsLogCount,
            'sleepConsistency' => $sleepConsistency,
            'moodRatings' => $moodRatings,
            'goalsByCategory' => $goalsByCategory,
            'moodRatings30days' => $moodRatings30days,
            'moodDistribution' => $moodDistribution,
            'moodLoggingRate' => $moodLoggingRate,
            'loggedDays' => $loggedDays,
            'totalDays' => $totalDays,
            'emotionsDistribution' => $emotionsDistribution,
            'exerciseLogs' => $exerciseLogs,
            'exerciseLogsLast4Weeks' => $exerciseLogsLast4Weeks,
            'weekLabels' => $weekLabels,
            'durations' => $durations,
            'exerciseChartData' => $exerciseChartData,
            'exerciseTypesDistribution' => $exerciseTypesDistribution,
        ]);
    }

    public function mood()
    {
        //<!-- line graph of mood over time (30 days)-->
        $moodRatings = auth()->user()->moodLogs()
            ->where('MoodDate', '>=', now()->subDays(30))
            ->orderBy('MoodDate')
            ->get(['MoodDate', 'MoodRating']);

        return view('health-insights.health-insights', [
            'currentTab' => 'mood',
            'moodRatings' => $moodRatings
        ]);
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
