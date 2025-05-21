<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SleepLog;

class SleepLogController extends Controller
{
    // Show the sleep logging dashboard
    public function index(Request $request) 
    {
        $sleepLogs = auth()->user()->sleepLogs()->with('user')->paginate(10);

        $weeklyLogsQuery = auth()->user()->sleepLogs()
            ->whereBetween('SleepDate', [now()->startOfWeek(), now()->endOfWeek()]);

        $averageSleepDurationRaw = $weeklyLogsQuery->avg('SleepDurationMinutes');
        $averageSleepQualityRaw = $weeklyLogsQuery->avg('SleepQuality');

        $nightsLoggedThisWeek = $weeklyLogsQuery
            ->distinct('SleepDate')
            ->count('SleepDate');

        // Streak calculation
        $sleepLogsByDate = auth()->user()->sleepLogs()
            ->orderBy('SleepDate', 'desc')
            ->pluck('SleepDate')
            ->map(fn ($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values();

        $streak = 0;
        $currentDate = \Carbon\Carbon::yesterday();
        $sleepLogDatesSet = collect($sleepLogsByDate)->flip();

        if ($sleepLogDatesSet->has($currentDate->format('Y-m-d'))) {
            while ($sleepLogDatesSet->has($currentDate->format('Y-m-d'))) {
                $streak++;
                $currentDate->subDay();
            }
        }

        $sleepLogStreak = $streak;

        // Emoji map
        $sleepQualityMap = [
            1 => ['emoji' => '😣', 'label' => 'Very Poor Quality'],
            2 => ['emoji' => '😩', 'label' => 'Poor Quality'],
            3 => ['emoji' => '😐', 'label' => 'Fair Quality'],
            4 => ['emoji' => '😊', 'label' => 'Good Quality'],
            5 => ['emoji' => '😴', 'label' => 'Excellent Quality']
        ];

        // Add emojis to each sleep log
        $sleepLogs->transform(function ($log) use ($sleepQualityMap) {
            $log->SleepQualityEmoji = $sleepQualityMap[$log->SleepQuality]['emoji'] ?? '❓';
            $log->SleepQualityLabel = $sleepQualityMap[$log->SleepQuality]['label'] ?? 'Unknown';
            return $log;
        });

        // Format average sleep duration
        if ($averageSleepDurationRaw !== null) {
            $averageHours = floor($averageSleepDurationRaw / 60);
            $averageMinutes = $averageSleepDurationRaw % 60;
            $averageSleepDuration = sprintf('%dh %02dm', $averageHours, $averageMinutes);
        } else {
            $averageSleepDuration = '❓';
        }

        // Format average sleep quality
        if ($averageSleepQualityRaw !== null) {
            $roundedQuality = round($averageSleepQualityRaw);
            $averageSleepQualityEmoji = $sleepQualityMap[$roundedQuality]['emoji'] ?? '❓';
            $averageSleepQualityLabel = $sleepQualityMap[$roundedQuality]['label'] ?? 'Unknown';
        } else {
            $averageSleepQualityEmoji = '❓';
            $averageSleepQualityLabel = 'Unknown';
        }

        return view('sleep.sleep', compact(
            'sleepLogs',
            'averageSleepDuration',
            'averageSleepQualityEmoji',
            'averageSleepQualityLabel',
            'nightsLoggedThisWeek',
            'sleepLogStreak'
        ));
    }


    // Show the sleep log form
    public function log(Request $request) 
    {
        return view('sleep.log-sleep');
    }

    // Store a new sleep log
    public function store(Request $request) 
    {
        // Validate the request
        $request->validate([
            'SleepDate' => 'required|date',
            'SleepDurationMinutes' => 'required|integer|min:0',
            'SleepQuality' => 'required|integer|between:1,5',
            'Notes' => 'nullable|string|max:255',
        ]);

        // Create a new sleep log
        $sleepLog = new SleepLog();
        $sleepLog->UserID = auth()->user()->id;
        $sleepLog->SleepDate = $request->input('SleepDate');
        $sleepLog->SleepDurationMinutes = $request->input('SleepDurationMinutes');
        $sleepLog->SleepQuality = $request->input('SleepQuality');
        $sleepLog->Notes = $request->input('Notes');
        $sleepLog->created_at = now();
        $sleepLog->save();

        // Redirect to the sleep log index with a success message
        return redirect()->route('sleep.index')->with('success', 'Sleep log created successfully.');
    }

    // Show the sleep log edit form
    public function edit($id) 
    {
        // Find the sleep log by ID
        $sleepLog = SleepLog::findOrFail($id);

        // Check if the authenticated user is the owner of the sleep log
        if ($sleepLog->UserID !== auth()->user()->id) {
            return redirect()->route('sleep.index')->with('error', 'You do not have permission to edit this sleep log.');
        }

        // Return the view with the sleep log data
        return view('sleep.edit-sleep', compact('sleepLog'));
    }

    // Update the sleep log
    public function update(Request $request, $id) 
    {
        // Validate the request
        $request->validate([
            'SleepDate' => 'required|date',
            'SleepDurationMinutes' => 'required|integer|min:0',
            'SleepQuality' => 'required|integer|between:1,5',
            'Notes' => 'nullable|string|max:255',
        ]);

        // Find the sleep log by ID
        $sleepLog = SleepLog::findOrFail($id);

        // Check if the authenticated user is the owner of the sleep log
        if ($sleepLog->UserID !== auth()->user()->id) {
            return redirect()->route('sleep.index')->with('error', 'You do not have permission to edit this sleep log.');
        }

        // Update the sleep log
        $sleepLog->SleepDate = $request->input('SleepDate');
        $sleepLog->SleepDurationMinutes = $request->input('SleepDurationMinutes');
        $sleepLog->SleepQuality = $request->input('SleepQuality');
        $sleepLog->Notes = $request->input('Notes');
        $sleepLog->save();

        // Redirect to the sleep log index with a success message
        return redirect()->route('sleep.index')->with('success', 'Sleep log updated successfully.');
    }

}
