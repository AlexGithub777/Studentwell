<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodLog;

class MoodTrackingController extends Controller
{
    /**
     * Display the mood tracking page with all mood logs and metrics.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Load all mood logs for the authenticated user
        $moodLogs = auth()->user()->moodLogs()->with('user')->paginate(10);

        // Load mood metrics (todays mood, avergae mood this week, Days tracked this week and mood log streak)

        // Get today's mood log if it exists
        $todayMood = auth()->user()->moodLogs()
            ->whereDate('MoodDate', now())
            ->first();

        // Calculate average mood for the current week
        $averageMood = auth()->user()->moodLogs()
            ->whereBetween('MoodDate', [now()->startOfWeek(), now()->endOfWeek()])
            ->avg('MoodRating');

        // Count distinct days with mood logs this week
        $daysTrackedThisWeek = auth()->user()->moodLogs()
            ->whereBetween('MoodDate', [now()->startOfWeek(), now()->endOfWeek()])
            ->distinct('MoodDate')
            ->count('MoodDate');

        // Calculate mood streak (consecutive days with logs starting today or yesterday)
        $moodLogsByDate = auth()->user()->moodLogs()
            ->orderBy('MoodDate', 'desc')
            ->pluck('MoodDate')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values();

        // Initialize streak counter
        $streak = 0;
        // Get today's date
        $today = \Carbon\Carbon::today();

        // Check if today or yesterday's mood log exists
        foreach ($moodLogsByDate as $date) {
            $logDate = \Carbon\Carbon::parse($date);

            // if streak is 0, check if today or yesterday's mood log exists
            if ($streak === 0) {
                // Check if today or yesterday's mood log exists
                if ($logDate->equalTo($today) || $logDate->equalTo($today->copy()->subDay())) {
                    // Start the streak
                    $streak++;
                } else {
                    break; // if neither today nor yesterday's log exists, break the loop
                }
            } else { // if streak is greater than 0, check for consecutive days
                // Check if the previous date is consecutive
                $previousDate = \Carbon\Carbon::parse($moodLogsByDate[$streak - 1]);
                // If the log date is the previous date minus one day, increment the streak
                if ($logDate->equalTo($previousDate->copy()->subDay())) {
                    $streak++;
                } else { // if the log date is not consecutive, break the loop
                    break;
                }
            }
        }

        // Set the mood log streak
        $moodLogStreak = $streak;

        // Define the mood rating map with emojis and labels
        $moodMap = [
            1 => ['emoji' => '😢', 'label' => 'Sad'],
            2 => ['emoji' => '😔', 'label' => 'Down'],
            3 => ['emoji' => '😐', 'label' => 'Okay'],
            4 => ['emoji' => '😊', 'label' => 'Good'],
            5 => ['emoji' => '😄', 'label' => 'Great'],
        ];

        // map MoodRating to emoji and string
        $moodLogs->transform(function ($moodLog) use ($moodMap) {
            $rating = $moodLog->MoodRating;
            $moodLog->MoodEmoji = $moodMap[$rating]['emoji'] ?? '❓';
            $moodLog->MoodLabel = $moodMap[$rating]['label'] ?? 'Unknown';
            return $moodLog;
        });

        // If today's mood exists, map its rating to emoji and label
        if ($todayMood) {
            $rating = $todayMood->MoodRating;
            $todayMood->MoodEmoji = $moodMap[$rating]['emoji'] ?? '❓';
            $todayMood->MoodLabel = $moodMap[$rating]['label'] ?? 'Unknown';
        }

        // If average mood is numeric, round it and map to emoji and label
        if (is_numeric($averageMood)) {
            $rounded = round($averageMood);
            $averageMoodEmoji = $moodMap[$rounded]['emoji'] ?? '❓';
            $averageMoodLabel = $moodMap[$rounded]['label'] ?? 'Unknown';
        } else {
            $averageMoodEmoji = null;
            $averageMoodLabel = null;
        }

        // Return the view and pass the mood logs
        return view('mood.mood', compact(
            'moodLogs',
            'todayMood',
            'averageMoodEmoji',
            'averageMoodLabel',
            'daysTrackedThisWeek',
            'moodLogStreak'
        ));
    }

    /** Display the mood tracking form.
     * 
     * 
     * @return \Illuminate\View\View
     * @return \Illuminate\Http\RedirectResponse
     */
    public function track()
    {
        // Return the view and pass the mood log
        return view('mood.track-mood');
    }

    /** Store a new mood log.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to log a mood.');
        }

        // Validate the request data
        $validatedData = $request->validate([
            'MoodId' => ['required', 'integer', 'min:1', 'max:5'],
            'MoodDate' => ['required', 'date', 'before_or_equal:today'],
            'Emotions' => ['nullable', 'array', 'max:5'],
            'Reflection' => ['nullable', 'string', 'max:255'],
        ], [
            'MoodId.required' => 'Mood rating is required.',
            'MoodId.integer' => 'Mood rating must be an integer.',
            'MoodId.min' => 'Mood rating must be at least 1.',
            'MoodId.max' => 'Mood rating cannot be greater than 5.',

            'MoodDate.required' => 'The mood date is required.',
            'MoodDate.date' => 'The mood date must be a valid date.',
            'MoodDate.before_or_equal' => 'You cannot select a future date.',

            'Emotions.array' => 'Emotions must be sent as an array.',
            'Emotions.max' => 'You can select up to 5 emotions only.',

            'Reflection.string' => 'Reflection must be a valid string.',
            'Reflection.max' => 'Reflection cannot exceed 255 characters.',
        ]);

        // Get the authenticated user's ID and the mood date
        $userId = auth()->id();
        $moodDate = $validatedData['MoodDate'];

        // Check if a log already exists for this user on that date
        $existingLog = MoodLog::where('UserID', $userId)
            ->whereDate('MoodDate', $moodDate)
            ->first();

        // If a log exists, redirect back with an error message
        if ($existingLog) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You have already logged a mood for this date.');
        }

        // Create a new mood log
        MoodLog::create([
            'UserID' => $userId,
            'MoodRating' => $validatedData['MoodId'],
            'MoodDate' => $moodDate,
            'Emotions' => json_encode($validatedData['Emotions'] ?? []),
            'Reflection' => $validatedData['Reflection'],
        ]);

        // Redirect to the mood index with a success message
        return redirect()->route('mood.index')->with('success', 'Mood logged successfully!');
    }

    /** Display the form to edit a mood log.
     * 
     * @param int $id
     * @return \Illuminate\View\View
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to edit a mood log.');
        }

        // Find the mood log by ID
        $moodLog = auth()->user()->moodLogs()->findOrFail($id);

        // Return the view and pass the mood log
        return view('mood.edit-mood', compact('moodLog'));
    }

    /** Update an existing mood log.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to update a mood log.');
        }

        // Find the mood log by ID first
        $moodLog = MoodLog::findOrFail($id);

        // Check if the authenticated user is the owner of the mood log
        if ($moodLog->UserID !== auth()->user()->id) {
            return redirect()->route('mood.index')->with('error', 'You do not have permission to edit this mood log.');
        }

        // Validate the request data
        $validatedData = $request->validate([
            'MoodId' => ['required', 'integer', 'min:1', 'max:5'],
            'MoodDate' => ['required', 'date', 'before_or_equal:today'],
            'Emotions' => ['nullable', 'array', 'max:5'],
            'Reflection' => ['nullable', 'string', 'max:255'],
        ], [
            'MoodId.required' => 'Mood rating is required.',
            'MoodId.integer' => 'Mood rating must be an integer.',
            'MoodId.min' => 'Mood rating must be at least 1.',
            'MoodId.max' => 'Mood rating cannot be greater than 5.',
            'MoodDate.required' => 'The mood date is required.',
            'MoodDate.date' => 'The mood date must be a valid date.',
            'MoodDate.before_or_equal' => 'You cannot select a future date.',
            'Emotions.array' => 'Emotions must be sent as an array.',
            'Emotions.max' => 'You can select up to 5 emotions only.',
            'Reflection.string' => 'Reflection must be a valid string.',
            'Reflection.max' => 'Reflection cannot exceed 255 characters.',
        ]);

        // Get the authenticated user and the mood date
        $user = auth()->user();
        $moodDate = $validatedData['MoodDate'];

        // Check for existing mood log for this user and date, excluding the current one
        $existingLog = $user->moodLogs()
            ->whereDate('MoodDate', $moodDate)
            ->where('MoodLogID', '!=', $id) // exclude the current mood log
            ->first();

        // If a log exists for the same date, redirect back with an error message
        if ($existingLog) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You already have a mood logged for that date.');
        }

        // Update fields
        $moodLog->MoodRating = $validatedData['MoodId'];
        $moodLog->MoodDate = $moodDate;
        $moodLog->Emotions = isset($validatedData['Emotions']) ? json_encode($validatedData['Emotions']) : null;
        $moodLog->Reflection = $validatedData['Reflection'] ?? null;
        $moodLog->save();

        // Redirect to the mood index with a success message
        return redirect()->route('mood.index')->with('success', 'Mood updated successfully!');
    }
}
