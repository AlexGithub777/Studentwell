<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SleepLog;
use Carbon\Carbon;

class SleepLogController extends Controller
{
    /**
     * Display the sleep log index page with user's sleep logs and statistics.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Fetch the user's sleep logs with pagination
        $sleepLogs = auth()->user()->sleepLogs()->with('user')->paginate(10);

        // Calculate average sleep duration and quality for the current week
        $weeklyLogsQuery = auth()->user()->sleepLogs()
            ->whereBetween('SleepDate', [now()->startOfWeek(), now()->endOfWeek()]);

        // Calculate average sleep duration and quality
        $averageSleepDuration = null;
        $averageSleepDurationRaw = $weeklyLogsQuery->avg('SleepDurationMinutes');
        $averageSleepQualityRaw = $weeklyLogsQuery->avg('SleepQuality');

        // calculate nights logged this week
        $nightsLoggedThisWeek = $weeklyLogsQuery
            ->distinct('SleepDate')
            ->count('SleepDate');

        // Streak calculation
        $sleepLogsByDate = auth()->user()->sleepLogs()
            ->orderBy('SleepDate', 'desc')
            ->pluck('SleepDate')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values();

        // Initialize streak counter
        $streak = 0;
        $currentDate = \Carbon\Carbon::today();
        $sleepLogDatesSet = collect($sleepLogsByDate)->flip();

        // Check today first
        if ($sleepLogDatesSet->has($currentDate->format('Y-m-d'))) {
            // Today has a log, count backwards from today
            while ($sleepLogDatesSet->has($currentDate->format('Y-m-d'))) {
                $streak++;
                $currentDate->subDay();
            }
        } else {
            // Today doesn't have a log, check yesterday
            $currentDate->subDay(); // Move to yesterday

            if ($sleepLogDatesSet->has($currentDate->format('Y-m-d'))) {
                // Yesterday has a log, count backwards from yesterday
                while ($sleepLogDatesSet->has($currentDate->format('Y-m-d'))) {
                    $streak++;
                    $currentDate->subDay();
                }
            } else {
                // Neither today nor yesterday have logs, check day before yesterday
                $currentDate->subDay(); // Move to day before yesterday

                if ($sleepLogDatesSet->has($currentDate->format('Y-m-d'))) {
                    // Day before yesterday has a log, count backwards
                    while ($sleepLogDatesSet->has($currentDate->format('Y-m-d'))) {
                        $streak++;
                        $currentDate->subDay();
                    }
                }
                // If even day before yesterday is missing, streak = 0 (2+ day gap)
            }
        }

        // If streak is 0, it means no logs for today, yesterday, or the day before yesterday
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

        // Return the view with all the data
        return view('sleep.sleep', compact(
            'sleepLogs',
            'averageSleepDuration',
            'averageSleepQualityEmoji',
            'averageSleepQualityLabel',
            'nightsLoggedThisWeek',
            'sleepLogStreak'
        ));
    }


    /**
     * Show the form to log a new sleep session.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function log(Request $request)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to log sleep.');
        }

        // return the view for logging sleep
        return view('sleep.log-sleep');
    }

    /**
     * Store a new sleep log entry.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to log sleep.');
        }

        // Validate the request data
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

        // Parse the sleep date and times
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

        // Adjust sleep date for early morning bedtimes
        $adjustedSleepDate = $bedTime->copy();

        // Only adjust if bedtime is early morning AND wake time suggests it's not a full night's sleep
        if ($bedTime->hour >= 0 && $bedTime->hour < 6) {
            // If wake time is in afternoon/evening (suggesting a nap or unusual schedule)
            // OR if it's a very short sleep (< 4 hours), don't adjust
            $sleepDuration = $wakeTime->diffInHours($bedTime);
            $wakeHour = $wakeTime->hour;

            // Only adjust if it looks like a normal night's sleep
            if ($sleepDuration >= 4 && $wakeHour >= 6 && $wakeHour <= 12) {
                $adjustedSleepDate->subDay();
            }
        }

        // Convert adjusted sleep date to a date string
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

        // Create the sleep log entry
        SleepLog::create([
            'UserID' => auth()->id(),
            'SleepDate' => $adjustedDateOnly,
            'BedTime' => $validatedData['BedTime'],
            'WakeTime' => $validatedData['WakeTime'],
            'SleepDurationMinutes' => $sleepDuration,
            'SleepQuality' => $validatedData['QualityId'],
            'Notes' => $validatedData['Notes'] ?? null,
        ]);

        // Redirect back to the sleep log index with success message
        return redirect()->route('sleep.index')->with('success', 'Sleep log created successfully.');
    }

    /**
     * Show the form to edit an existing sleep log.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to edit sleep logs.');
        }

        // Find the sleep log by ID qnd redirect if not found
        $sleepLog = SleepLog::find($id);
        if (!$sleepLog) {
            return redirect()->route('sleep.index')->with('error', 'Sleep log not found.');
        }

        // Check if the authenticated user is the owner of the sleep log
        if ($sleepLog->UserID !== auth()->user()->id) {
            return redirect()->route('sleep.index')->with('error', 'You do not have permission to edit this sleep log.');
        }

        // Return the view with the sleep log data
        return view('sleep.edit-sleep', compact('sleepLog'));
    }

    /**
     * Update an existing sleep log entry.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to edit sleep logs.');
        }

        // Find the sleep log by ID and redirect if not found
        $sleepLog = SleepLog::find($id);
        if (!$sleepLog) {
            return redirect()->route('sleep.index')->with('error', 'Sleep log not found.');
        }

        // Check if the authenticated user is the owner of the sleep log
        if ($sleepLog->UserID !== auth()->user()->id) {
            return redirect()->route('sleep.index')->with('error', 'You do not have permission to edit this sleep log.');
        }

        // Validate the request (matching store function validation)
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

        // Parse the sleep date and times
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
                ->withErrors(['WakeTime' => "You cannot log a sleep session that hasn't finished yet."])
                ->withInput();
        }

        // Adjust sleep date for early morning bedtimes
        $adjustedSleepDate = $bedTime->copy();

        // Only adjust if bedtime is early morning AND wake time suggests it's not a full night's sleep
        if ($bedTime->hour >= 0 && $bedTime->hour < 6) {
            // If wake time is in afternoon/evening (suggesting a nap or unusual schedule)
            // OR if it's a very short sleep (< 4 hours), don't adjust
            $sleepDuration = $wakeTime->diffInHours($bedTime);
            $wakeHour = $wakeTime->hour;

            // Only adjust if it looks like a normal night's sleep
            if ($sleepDuration >= 4 && $wakeHour >= 6 && $wakeHour <= 12) {
                $adjustedSleepDate->subDay();
            }
        }

        $adjustedDateOnly = $adjustedSleepDate->toDateString();

        // Fetch all existing logs for this user on the adjusted sleep date (excluding current log being edited)
        $existingLogs = \App\Models\SleepLog::where('UserID', auth()->id())
            ->where('SleepDate', $adjustedDateOnly)
            ->where('SleepLogID', '!=', $id) // Exclude the current log being updated
            ->get();

        // Enforce only 1 sleep log per adjusted sleep date
        if ($existingLogs->count() >= 1) {
            return back()
                ->withErrors(['SleepDate' => 'You can only log one sleep session per day.'])
                ->withInput();
        }

        // Check for overlapping sleep times
        foreach ($existingLogs as $log) {
            $existingBedTime = \Carbon\Carbon::parse("{$log->SleepDate} {$log->BedTime}");
            $existingWakeTime = \Carbon\Carbon::parse("{$log->SleepDate} {$log->WakeTime}");

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
        $sleepDurationMinutes = $bedTime->diffInMinutes($wakeTime, false);

        // Update the sleep log
        $sleepLog->update([
            'SleepDate' => $adjustedDateOnly,
            'BedTime' => $validatedData['BedTime'],
            'WakeTime' => $validatedData['WakeTime'],
            'SleepDurationMinutes' => $sleepDurationMinutes,
            'SleepQuality' => $validatedData['QualityId'],
            'Notes' => $validatedData['Notes'] ?? null,
        ]);

        return redirect()->route('sleep.index')->with('success', 'Sleep log updated successfully.');
    }
}
