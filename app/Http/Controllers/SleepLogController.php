<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SleepLog;
use Carbon\Carbon;

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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'SleepDate' => ['required', 'date', 'before_or_equal:today'],
            'BedTime' => ['required', 'date_format:H:i'],
            'WakeTime' => ['required', 'date_format:H:i'],
            'QualityId' => ['required', 'integer', 'between:1,5'],
            'Notes' => ['nullable', 'string', 'max:255'],
        ], [
            'SleepDate.required' => 'The sleep date is required.',
            'SleepDate.date' => 'The sleep date must be a valid date.',
            'SleepDate.before_or_equal' => 'You cannot select a future date.',

            'BedTime.required' => 'Bedtime is required.',
            'BedTime.date_format' => 'Bedtime must be in the format HH:MM.',

            'WakeTime.required' => 'Wake time is required.',
            'WakeTime.date_format' => 'Wake time must be in the format HH:MM.',

            'QualityId.required' => 'Sleep quality is required.',
            'QualityId.integer' => 'Sleep quality must be a number.',
            'QualityId.between' => 'Sleep quality must be between 1 and 5.',

            'Notes.string' => 'Notes must be a valid string.',
            'Notes.max' => 'Notes cannot exceed 255 characters.',
        ]);

        $sleepDate = \Carbon\Carbon::createFromFormat('Y-m-d', $validatedData['SleepDate']);
        $bedTime = $sleepDate->copy()->setTimeFromTimeString($validatedData['BedTime']);
        $wakeTime = $sleepDate->copy()->setTimeFromTimeString($validatedData['WakeTime']);

        // Cross-midnight adjustment
        if ($wakeTime->lessThanOrEqualTo($bedTime)) {
            $wakeTime->addDay();
        }

        // Disallow future bed time
        if ($bedTime->greaterThan(now())) {
            return back()
                ->withErrors(['BedTime' => 'You cannot log a sleep session that starts in the future.'])
                ->withInput();
        }

        // Disallow future wake time
        if ($wakeTime->greaterThan(now())) {
            return back()
                ->withErrors(['WakeTime' => 'You cannot log a sleep session that hasn’t finished yet.'])
                ->withInput();
        }

        // Adjust for early-morning sessions (shift to previous day if before 6am)
        $adjustedSleepDate = $bedTime->copy();
        if ($bedTime->hour < 6) {
            $adjustedSleepDate->subDay();
        }
        $adjustedDateOnly = $adjustedSleepDate->toDateString();

        // Fetch all existing logs for this user on the adjusted sleep date
        $existingLogs = \App\Models\SleepLog::where('UserID', auth()->id())
            ->where('SleepDate', $adjustedDateOnly)
            ->get();

        // Enforce only 1 sleep log per adjusted sleep date
        if ($existingLogs->count() >= 1) {
            return back()
                ->withErrors(['SleepDate' => 'You can only log one sleep session per day.'])
                ->withInput();
        }

        // Check for overlapping sleep times
        foreach ($existingLogs as $log) {
            $existingBedTime = Carbon::parse("{$log->SleepDate} {$log->BedTime}");
            $existingWakeTime = Carbon::parse("{$log->SleepDate} {$log->WakeTime}");

            // Adjust if crosses midnight
            if ($existingWakeTime->lessThanOrEqualTo($existingBedTime)) {
                $existingWakeTime->addDay();
            }

            // Adjust for early AM logs to keep consistency in overlap checks
            if ($existingBedTime->hour < 6) {
                $existingBedTime->subDay();
                $existingWakeTime->subDay();
            }

            // Overlap check
            if (
                $bedTime->between($existingBedTime, $existingWakeTime) ||
                $wakeTime->between($existingBedTime, $existingWakeTime) ||
                ($bedTime->lessThan($existingBedTime) && $wakeTime->greaterThan($existingWakeTime))
            ) {
                return back()
                    ->withErrors(['SleepDate' => 'This sleep session overlaps with an existing log.'])
                    ->withInput();
            }
        }

        // Calculate sleep duration in minutes
        $sleepDuration = $bedTime->diffInMinutes($wakeTime, false);

        SleepLog::create([
            'UserID' => auth()->id(),
            'SleepDate' => $adjustedDateOnly,
            'BedTime' => $validatedData['BedTime'],
            'WakeTime' => $validatedData['WakeTime'],
            'SleepDurationMinutes' => $sleepDuration,
            'SleepQuality' => $validatedData['QualityId'],
            'Notes' => $validatedData['Notes'] ?? null,
        ]);

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
