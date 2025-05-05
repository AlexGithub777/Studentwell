<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForumController;

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

Route::post('/signup', [UserController::class, 'signup'])->name('register');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::post('/signin', [UserController::class, 'signin'])-> name('login');


// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Account Routes
    Route::get('/account', [UserController::class, 'showAccount'])->name('account.show');

    Route::post('/account/edit', [UserController::class, 'editAccount'])->name('account.edit');

    Route::post('/account/delete', [UserController::class, 'deleteAccount'])->name('account.delete');

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
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/{id}/reply', [ForumController::class, 'reply'])->name('forum.reply');
    Route::post('/forum/{id}/like', [ForumController::class, 'likePost'])->name('forum.like.post');
    Route::post('/reply/{id}/like', [ForumController::class, 'likeReply'])->name('forum.like.reply');

});
