<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Home Routes

Route::get('/', function () {
    return view('home.home');
});

Route::get('/home', function () {
    return view('home.home');
});

// Authentication Routes

Route::get('/signup', function () {
    return view('authentication.signup');
});

Route::get('/signin', function () {
    return view('authentication.signin');
});

Route::post('/signup', [UserController::class, 'signup']);

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::post('/signin', [UserController::class, 'signin']);

// Account Routes

Route::get('/account', function () {
    return view('authentication.account');
});

// Admin Routes

Route::get('/dashboard', function () {
    return view('admin-dashboard.dashboard');
});

// Mood Routes

Route::get('/mood', function () {
    return view('mood.mood');
});

Route::get('/mood/track-mood', function () {
    return view('mood.track-mood');
});

// Exercise Routes

Route::get('/exercise', function () {
    return view('exercise.exercise');
});

Route::get('/exercise/log-exercise', function () {
    return view('exercise.log-exercise');
});

Route::get('/exercise/plan-exercise', function () {
    return view('exercise.plan-exercise');
});

Route::get('/exercise/{id}', function ($id) {
    return view('exercise.edit-exercise', ['id' => $id]);
});

// Goals Routes

Route::get('/goals', function () {
    return view('goals.goals');
});
Route::get('/goals/log-goal', function () {
    return view('goals.log-goal');
});
Route::get('/goals/set-goal', function () {
    return view('goals.set-goal');
});

Route::get('/goals/edit-goal/{id}', function ($id) {
    return view('goals.edit-goal');
});

// Sleep Routes

Route::get('/sleep', function () {
    return view('sleep.sleep');
});
Route::get('/sleep/log-sleep', function () {
    return view('sleep.log-sleep');
});

// Health Insights Routes

Route::get('/health-insights', function () {
    return view('health-insights.health-insights');
});

Route::get('/health-insights/mood', function () {
    return view('health-insights.mood-insights');
});

Route::get('/health-insights/exercise', function () {
    return view('health-insights.exercise-insights');
});

Route::get('/health-insights/sleep', function () {
    return view('health-insights.sleep-insights');
});

Route::get('/health-insights/goals', function () {
    return view('health-insights.goals-insights');
});

// Support Resources Routes

Route::get('/support-resources', function () {
    return view('support-resources.support-resources');
});

// Forum Routes
Route::get('/forum', function (Request $request) {
    //$posts = Post::all();//Query all posts from "posts" table and assign to $posts variable
    //pass the data $posts & $keywords to VIEW
    //return view('forum.forum', ['posts' => $posts]);
    return view('forum.forum');
});

Route::get('/forum/{id}', function ($id) {
    return view('forum.post-details', ['id' => $id]);
});

Route::get('/forum/create', function () {
    return view('forum.create-post');
});
