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
        // get the data for the overview tab
        // bar graph of logs across features (mood, exercise, sleep, goals) -->
        // get count of logs for mood
        $moodLogCount = auth()->user()->moodLogs()->count();
        // get count of logs for exercise
        $exerciseLogCount = auth()->user()->exerciseLogs()->count();
        // get count of logs for sleep
        $sleepLogCount = auth()->user()->sleepLogs()->count();
        // get count of logs for goals
        $goalsLogCount = auth()->user()->goalLogs()->count();

        $rawLogData = [
            'mood' => $moodLogCount,
            'exercise' => $exerciseLogCount,
            'sleep' => $sleepLogCount,
            'goals' => $goalsLogCount,
        ];

        // data for donut graph of sleep consistency (daily hours of sleep) count of <6hrs, 6-8 hrs, 8+ hours)
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


        // For the GROUP BY issue:
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        //<!-- pie chart of goals by category (exercise, sleep, mood) -->
        $goalsByCategory = auth()->user()->goals()
            ->selectRaw('GoalCategory, COUNT(*) as count, MAX(created_at) as latest_created_at, MAX(GoalTargetDate) as latest_goal_date')
            ->groupBy('GoalCategory')
            ->orderByRaw('MAX(GoalTargetDate) DESC')
            ->orderByRaw('MAX(created_at) DESC')
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

        $totalDays = 30;

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

        // <!-- pie chart of exercise types distribution -->
        $exerciseTypesDistribution = $exerciseLogs
            ->groupBy('ExerciseType')
            ->map(function ($group) {
                return $group->count();
            });

        // Sleep insights
        //<!-- line graph of sleep duration over time (30 days) -->
        $sleepLogs = auth()->user()->sleepLogs()
            ->where('SleepDurationMinutes', '>', 0)
            ->where('SleepDate', '>=', now()->subDays(30))
            ->orderBy('SleepDate')
            ->get(['SleepDate', 'SleepDurationMinutes', 'BedTime', 'WakeTime', 'SleepQuality']);

        //<!-- line graph of bedtime and wake-up time over time (30 days) use $sleepLogs -->

        //<!-- donut chart of sleep logging rate (if sleep is Logged or not logged in the last 30 days)
        $sleepLoggedCount = $sleepLogs->count();
        $totalSleepDays = 30; // we want to check for the last 30 days
        $sleepLoggingRate = $totalSleepDays > 0 ? round(($sleepLoggedCount / $totalSleepDays) * 100, 1) : 0;
        // calculate the sleep days logged and unlogged
        $sleepDaysLogged = $sleepLogs->pluck('SleepDate')->unique()->count();
        $sleepDaysUnlogged = $totalSleepDays - $sleepDaysLogged;

        //<!-- pie chart of sleep quality distribution (1, 2, 3) -->
        $sleepQualityDistribution = $sleepLogs
            ->groupBy('SleepQuality')
            ->map(function ($group) {
                return $group->count();
            });

        // Goals insights
        //<!-- line graph of goals completion rate (last 6 weeks)) -->
        $goalLogs = auth()->user()->goalLogs()
            ->where('GoalLogDate', '>=', now()->subWeeks(6)->startOfWeek())
            ->orderBy('GoalLogDate')
            ->get(['GoalLogDate', 'GoalStatus']);

        $weeks = collect(range(0, 5))->map(function ($i) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            return $startOfWeek->format('o-W');
        })->reverse();

        $weeklyCompletionRates = $weeks->map(function ($weekKey) use ($goalLogs) {
            [$year, $weekNumber] = explode('-', $weekKey);
            $start = Carbon::now()->setISODate((int)$year, (int)$weekNumber)->startOfWeek();
            $end = $start->copy()->endOfWeek();

            $weekLogs = $goalLogs->filter(function ($log) use ($start, $end) {
                $logDate = Carbon::parse($log->GoalLogDate);
                return $logDate->between($start, $end);
            });

            $total = $weekLogs->count();
            $completed = $weekLogs->where('GoalStatus', 'completed')->count();

            return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
        })->values();

        $weekGoalsLabels = $weeks->map(function ($weekKey) {
            [$year, $weekNumber] = explode('-', $weekKey);
            $start = Carbon::now()->setISODate((int)$year, (int)$weekNumber)->startOfWeek();
            return $start->format('d M');
        })->values();


        //<!-- bar graph of completed goals by category (Career, Finance, Wellness) -->
        $goalLogsAllTime = auth()->user()->goalLogs()
            ->where('GoalStatus', 'completed')
            ->with('goal')  // eager load related goal
            ->get();

        $completedGoalsByCategory = $goalLogsAllTime->groupBy(fn($log) => $log->goal->GoalCategory ?? 'Unknown')
            ->map(fn($group) => $group->count())
            ->map(function ($count, $category) {
                return ['GoalCategory' => $category, 'count' => $count];
            })
            ->values();



        //<!-- donut graph of goal completion status (completed, incomplete, partially, add unlogged and upcoming (where goal target gate is in the future))-->
        $goalStatusCounts = auth()->user()->goalLogs()
            ->select('GoalStatus', \DB::raw('COUNT(*) as count'))
            ->groupBy('GoalStatus')
            ->pluck('count', 'GoalStatus');

        // Clone counts so we can add extra status groups
        $fullGoalStatusCounts = $goalStatusCounts->toBase();

        // Fetch all user's goals that don't have logs
        $unloggedGoals = auth()->user()->goals()->doesntHave('goalLogs')->get();

        // Add unlogged and upcoming counts
        $unloggedCount = 0;
        $upcomingCount = 0;

        foreach ($unloggedGoals as $goal) {
            if ($goal->TargetDate > now()) {
                $upcomingCount++;
            } else {
                $unloggedCount++;
            }
        }

        // Append new statuses
        if ($unloggedCount > 0) {
            $fullGoalStatusCounts['unlogged'] = $unloggedCount;
        }
        if ($upcomingCount > 0) {
            $fullGoalStatusCounts['upcoming'] = $upcomingCount;
        }

        return view('health-insights.health-insights', [
            'currentTab' => $currentTab,
            'rawLogData' => $rawLogData,
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
            'sleepLogs' => $sleepLogs,
            'totalSleepDays' => $totalSleepDays,
            'sleepLoggingRate' => $sleepLoggingRate,
            'sleepDaysLogged' => $sleepDaysLogged,
            'sleepDaysUnlogged' => $sleepDaysUnlogged,
            'sleepQualityDistribution' => $sleepQualityDistribution,
            'fullGoalStatusCounts' => $fullGoalStatusCounts,
            'completedGoalsByCategory' => $completedGoalsByCategory,
            'weeklyCompletionRates' => $weeklyCompletionRates,
            'weekGoalsLabels' => $weekGoalsLabels,
        ]);
    }
}
