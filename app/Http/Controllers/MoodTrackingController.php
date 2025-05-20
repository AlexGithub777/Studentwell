<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoodTrackingController extends Controller
{
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

        // get the number of days tracked in a row all time
        $moodLogStreak = auth()->user()->moodLogs()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->filter(function ($count) {
                return $count > 0;
            })
            ->count();
        
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

        if ($averageMood) {
            $rounded = round($averageMood); // because it's a float
            $averageMoodEmoji = $moodMap[$rounded]['emoji'] ?? '❓';
            $averageMoodLabel = $moodMap[$rounded]['label'] ?? 'Unknown';
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

    public function track(Request $request)
    {
        // Validate the request
        $request->validate([
            'mood_rating' => 'required|integer|min:1|max:5',
            'emotions' => 'required|string|max:255',
            'reflection' => 'nullable|string|max:1000',
        ]);

        // Create a new mood log
        auth()->user()->moodLogs()->create([
            'MoodRating' => $request->input('mood_rating'),
            'Emotions' => $request->input('emotions'),
            'Reflection' => $request->input('reflection'),
        ]);

        // Redirect back with success message
        return redirect()->route('mood.index')->with('success', 'Mood logged successfully!');
    }

    public function edit($id)
    {
        // Find the mood log by ID
        $moodLog = auth()->user()->moodLogs()->findOrFail($id);

        // Return the view and pass the mood log
        return view('mood.edit-mood', compact('moodLog'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'mood_rating' => 'required|integer|min:1|max:5',
            'emotions' => 'required|string|max:255',
            'reflection' => 'nullable|string|max:1000',
        ]);

        // Find the mood log by ID
        $moodLog = auth()->user()->moodLogs()->findOrFail($id);

        // Update the mood log
        $moodLog->update([
            'MoodRating' => $request->input('mood_rating'),
            'Emotions' => $request->input('emotions'),
            'Reflection' => $request->input('reflection'),
        ]);

        // Redirect back with success message
        return redirect()->route('mood.index')->with('success', 'Mood updated successfully!');
    }
}
