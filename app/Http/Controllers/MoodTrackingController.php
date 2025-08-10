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
        $user = auth()->user();
        $filter = $request->query('filter');

        // Mood map
        $moodMap = [
            1 => ['emoji' => '😢', 'label' => 'Sad'],
            2 => ['emoji' => '😔', 'label' => 'Down'],
            3 => ['emoji' => '😐', 'label' => 'Okay'],
            4 => ['emoji' => '😊', 'label' => 'Good'],
            5 => ['emoji' => '😄', 'label' => 'Great'],
        ];

        // Query mood logs
        $query = $user->moodLogs()->with('user');

        // Apply filter if provided
        if ($filter) {
            // Reverse map MoodLabel to MoodRating
            $labelToRating = collect($moodMap)
                ->mapWithKeys(fn($item, $key) => [$item['label'] => $key]);

            $rating = $labelToRating[$filter] ?? null;

            if ($rating !== null) {
                $query->where('MoodRating', $rating);
            }
        }

        $moodLogs = $query->orderBy('MoodDate', 'desc')->paginate(10);

        // Add emoji/label to each mood log
        $moodLogs->transform(function ($log) use ($moodMap) {
            $rating = $log->MoodRating;
            $log->MoodEmoji = $moodMap[$rating]['emoji'] ?? '❓';
            $log->MoodLabel = $moodMap[$rating]['label'] ?? 'Unknown';
            return $log;
        });

        // Unique moods for dropdown
        $uniqueMoods = collect($moodMap)
            ->reverse() // Great to Sad
            ->map(function ($item, $rating) {
                return (object)[
                    'Rating' => $rating,
                    'MoodLabel' => $item['label'],
                    'MoodEmoji' => $item['emoji'],
                ];
            });


        // Today's mood
        $todayMood = $user->moodLogs()->whereDate('MoodDate', now())->first();
        if ($todayMood) {
            $rating = $todayMood->MoodRating;
            $todayMood->MoodEmoji = $moodMap[$rating]['emoji'] ?? '❓';
            $todayMood->MoodLabel = $moodMap[$rating]['label'] ?? 'Unknown';
        }

        // Average mood this week
        $averageMood = $user->moodLogs()
            ->whereBetween('MoodDate', [now()->startOfWeek(), now()->endOfWeek()])
            ->avg('MoodRating');

        if (is_numeric($averageMood)) {
            $rounded = round($averageMood);
            $averageMoodEmoji = $moodMap[$rounded]['emoji'] ?? '❓';
            $averageMoodLabel = $moodMap[$rounded]['label'] ?? 'Unknown';
        } else {
            $averageMoodEmoji = null;
            $averageMoodLabel = null;
        }

        // Days tracked this week
        $daysTrackedThisWeek = $user->moodLogs()
            ->whereBetween('MoodDate', [now()->startOfWeek(), now()->endOfWeek()])
            ->distinct('MoodDate')
            ->count('MoodDate');

        // Mood streak
        $moodLogsByDate = $user->moodLogs()
            ->orderBy('MoodDate', 'desc')
            ->pluck('MoodDate')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values();

        $today = \Carbon\Carbon::today();
        $streak = 0;
        foreach ($moodLogsByDate as $date) {
            $logDate = \Carbon\Carbon::parse($date);
            if ($streak === 0) {
                if ($logDate->equalTo($today) || $logDate->equalTo($today->copy()->subDay())) {
                    $streak++;
                } else {
                    break;
                }
            } else {
                $previousDate = \Carbon\Carbon::parse($moodLogsByDate[$streak - 1]);
                if ($logDate->equalTo($previousDate->copy()->subDay())) {
                    $streak++;
                } else {
                    break;
                }
            }
        }

        $moodLogStreak = $streak;

        return view('mood.mood', compact(
            'moodLogs',
            'moodMap',
            'uniqueMoods',
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
        $moodLog = new MoodLog();
        $moodLog->UserID = $userId;
        $moodLog->MoodDate = $moodDate;
        $moodLog->MoodRating = $validatedData['MoodId'];
        $moodLog->Emotions = isset($validatedData['Emotions']) ? json_encode($validatedData['Emotions']) : null;
        $moodLog->Reflection = $validatedData['Reflection'] ?? null;
        $moodLog->save();

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
        if ($moodLog->UserID != auth()->user()->id) {
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
