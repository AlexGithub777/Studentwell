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
        return view('health-insights.health-insights', ['currentTab' => 'overview']);
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
