<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportResource;

class SupportResourceController extends Controller
{
    /**
     * Display the support resources page with a list of all resources.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Load all support resources with their categories
        $resources = SupportResource::with('category')->get();

        // Return the view and pass the resources
        return view('support-resources.support-resources', compact('resources'));
    }
}
