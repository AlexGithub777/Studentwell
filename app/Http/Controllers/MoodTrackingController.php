<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodLog;

class MoodTrackingController extends Controller
{
    // Show the mood tracking dashboard
    public function index(Request $request)
    {
        // Load all mood logs for the authenticated user
        $moodLogs = auth()->user()->moodLogs()->with('user')->paginate(10); 

        // Load mood metrics (todays mood, avergae mood this week, Days tracked this week and mood log streak)
        $todayMood = auth()->user()->moodLogs()
            ->whereDate('created_at', now())
            ->first();

        $averageMood = auth()->user()->moodLogs()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->avg('MoodRating');

        $daysTrackedThisWeek = auth()->user()->moodLogs()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->distinct('created_at')
            ->count('created_at');

        // Calculate mood streak (consecutive days with logs starting today or yesterday)
        $moodLogsByDate = auth()->user()->moodLogs()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($log) {
                return \Carbon\Carbon::parse($log->created_at)->format('Y-m-d');
            })
            ->keys()
            ->sortDesc()
            ->values();

        $streak = 0;
        $today = \Carbon\Carbon::today();

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

        if ($todayMood) {
            $rating = $todayMood->MoodRating;
            $todayMood->MoodEmoji = $moodMap[$rating]['emoji'] ?? '❓';
            $todayMood->MoodLabel = $moodMap[$rating]['label'] ?? 'Unknown';
        }

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

    // Show the mood tracking form
    public function track()
    {
        // Return the view and pass the mood log
        return view('mood.track-mood');
    }


    // Store mood log
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'MoodId' => ['required', 'integer', 'min:1', 'max:5'],
            'Emotions' => ['nullable', 'array', 'max:5'],  // max 5 emotions selected
            'Reflection' => ['nullable', 'string', 'max:255'],
        ], [
            'MoodId.required' => 'Mood rating is required.',
            'MoodId.integer' => 'Mood rating must be an integer.',
            'MoodId.min' => 'Mood rating must be at least 1.',
            'MoodId.max' => 'Mood rating cannot be greater than 5.',
    
            'Emotions.array' => 'Emotions must be sent as an array.',
            'Emotions.max' => 'You can select up to 5 emotions only.', // <-- add this message
    
            'Reflection.string' => 'Reflection must be a valid string.',
            'Reflection.max' => 'Reflection cannot exceed 255 characters.',
        ]);

        $userId = auth()->user()->id;

        // Check if a mood log already exists for today
        $existingLog = MoodLog::where('UserID', $userId)
            ->whereDate('created_at', now())
            ->first();

        if ($existingLog) {
            return redirect()->back()
                ->with('error', 'You have already logged your mood for today.');
        }

        MoodLog::create([
            'MoodRating' => $validatedData['MoodId'],
            'Emotions' => json_encode($validatedData['Emotions'] ?? []),
            'Reflection' => $validatedData['Reflection'],
            'UserID' => $userId,
        ]);

        return redirect()->route('mood.index')->with('success', 'Mood logged successfully!');
    }

    // Show the mood log edit form
    public function edit($id)
    {
        // Find the mood log by ID
        $moodLog = auth()->user()->moodLogs()->findOrFail($id);

        // Return the view and pass the mood log
        return view('mood.edit-mood', compact('moodLog'));
    }

    // Update mood log
    public function update(Request $request, $id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'MoodId' => ['required', 'integer', 'min:1', 'max:5'],
            'Emotions' => ['nullable', 'array', 'max:5'],  // max 5 emotions selected
            'Reflection' => ['nullable', 'string', 'max:255'],
        ], [
            'MoodId.required' => 'Mood rating is required.',
            'MoodId.integer' => 'Mood rating must be an integer.',
            'MoodId.min' => 'Mood rating must be at least 1.',
            'MoodId.max' => 'Mood rating cannot be greater than 5.',
    
            'Emotions.array' => 'Emotions must be sent as an array.',
            'Emotions.max' => 'You can select up to 5 emotions only.', // <-- add this message
    
            'Reflection.string' => 'Reflection must be a valid string.',
            'Reflection.max' => 'Reflection cannot exceed 255 characters.',
        ]);

        // Find the mood log by ID
        $moodLog = auth()->user()->moodLogs()->findOrFail($id);

        // Update the mood log
        $moodLog->MoodRating = $validatedData['MoodId'];

        // Check if emotions are provided
        if (isset($validatedData['Emotions'])) {
            $moodLog->Emotions = json_encode($validatedData['Emotions']);
        } else {
            $moodLog->Emotions = null;
        }

        // Check if reflection is provided
        if (isset($validatedData['Reflection'])) {
            $moodLog->Reflection = $validatedData['Reflection'];
        } else {
            $moodLog->Reflection = null;
        }

        // Save the mood log
        $moodLog->save();

        // Redirect back with success message
        return redirect()->route('mood.index')->with('success', 'Mood updated successfully!');
    }
}
