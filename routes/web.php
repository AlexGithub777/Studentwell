<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupportResourceController;
use App\Http\Controllers\MoodTrackingController;
use App\Http\Controllers\SleepLogController;
use App\Http\Controllers\GoalSettingController;
use App\Http\Controllers\ExerciseController;

// Home Routes
Route::get('/', function () {
    return view('home.home');
});

Route::get('/home', function () {
    return view('home.home');
});

// Support Resources Routes
Route::get('/support-resources', [SupportResourceController::class, 'index'])->name('support-resources.index');

// Authentication Routes

Route::get('/signup', function () {
    return view('authentication.signup');
});

Route::get('/signin', function () {
    return view('authentication.signin');
});

Route::post('/signup', [UserController::class, 'signup'])->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/signin', [UserController::class, 'signin'])->name('login');


// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Account Routes
    Route::get('/account', [UserController::class, 'showAccount'])->name('account.show');
    Route::post('/account/edit', [UserController::class, 'editAccount'])->name('account.edit');
    Route::post('/account/delete', [UserController::class, 'deleteAccount'])->name('account.delete');

    // Admin-only routes
    Route::middleware('IsAdmin')->prefix('admin')->as('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Support Resources Routes
        Route::get('/add-resource', [AdminController::class, 'addResourcePage'])->name('add.resource');
        Route::post('/add-resource', [AdminController::class, 'addResource'])->name('store.resource');
        Route::get('/edit-resource/{id}', [AdminController::class, 'editResourcePage'])->name('edit.resource');
        Route::put('/edit-resource/{id}', [AdminController::class, 'updateResource'])->name('update.resource');
        Route::delete('/delete-resource/{id}', [AdminController::class, 'deleteResource'])->name('delete.resource');

        // User Management Routes
        Route::get('/edit-user/{id}', [AdminController::class, 'editUserPage'])->name('edit.user');
        Route::put('/edit-user/{id}', [AdminController::class, 'updateUser'])->name('update.user');
        Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('delete.user');
    });

    //  Mood Routes
    Route::get('/mood', [MoodTrackingController::class, 'index'])->name('mood.index');
    Route::get('/mood/track-mood', [MoodTrackingController::class, 'track'])->name('mood.track');
    Route::post('/mood/track-mood', [MoodTrackingController::class, 'store'])->name('mood.store');
    Route::get('/mood/edit-mood/{id}', [MoodTrackingController::class, 'edit'])->name('mood.edit');
    Route::put('/mood/edit-mood/{id}', [MoodTrackingController::class, 'update'])->name('mood.update');

    // Exercise Routes
    Route::get('/exercise', [ExerciseController::class, 'index'])->name('exercise.index');
    Route::get('/exercise/plan-exercise', [ExerciseController::class, 'addPlannedExercisePage'])->name('exercise.plan');
    Route::post('/exercise/plan-exercise', [ExerciseController::class, 'storePlannedExercise'])->name('exercise.store.plan');
    Route::get('/log-exercise/{plannedExerciseID}', [ExerciseController::class, 'logExercisePage'])->name('exercise.log');
    Route::get('/log-exercise', [ExerciseController::class, 'logExercisePage'])->name('exercise.log.unplanned');
    Route::post('/log-exercise/{plannedExerciseID?}', [ExerciseController::class, 'storeLoggedExercise'])->name('exercise.store.log');

    Route::get('/exercise/edit-exercise/{id}', [ExerciseController::class, 'editPlannedExercisePage'])->name('exercise.edit');
    Route::put('/exercise/edit-exercise/{id}', [ExerciseController::class, 'updatePlannedExercise'])->name('exercise.update');

    // Goals Routes
    Route::get('/goals', [GoalSettingController::class, 'index'])->name('goals.index');
    Route::get('/goals/log-goal/{id}', [GoalSettingController::class, 'log'])->name('goals.log');
    Route::post('/goals/log-goal/{id}', [GoalSettingController::class, 'storeLog'])->name('goals.store.log');
    Route::get('/goals/edit-goal/{id}', [GoalSettingController::class, 'edit'])->name('goals.edit');
    Route::put('/goals/edit-goal/{id}', [GoalSettingController::class, 'update'])->name('goals.update');
    Route::get('/goals/set-goal', [GoalSettingController::class, 'set'])->name('goals.set');
    Route::post('/goals/set-goal', [GoalSettingController::class, 'store'])->name('goals.store.set');

    // Sleep Routes
    Route::get('/sleep', [SleepLogController::class, 'index'])->name('sleep.index');
    Route::get('/sleep/log-sleep', [SleepLogController::class, 'log'])->name('sleep.log');
    Route::post('/sleep/log-sleep', [SleepLogController::class, 'store'])->name('sleep.store');
    Route::get('/sleep/edit-sleep/{id}', [SleepLogController::class, 'edit'])->name('sleep.edit');
    Route::put('/sleep/edit-sleep/{id}', [SleepLogController::class, 'update'])->name('sleep.update');

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

    // Forum Routes
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/post/{id}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/post/{id}/reply', [ForumController::class, 'reply'])->name('forum.reply');
    Route::post('/forum/post/{forum_post}/like', [ForumController::class, 'likePost'])->name('forum.like.post');
    Route::post('/forum/reply/{forum_reply}/like', [ForumController::class, 'likeReply'])->name('forum.like.reply');
    Route::delete('/forum/post/{id}', [ForumController::class, 'delete'])->name('forum.delete');
    Route::delete('/forum/reply/{id}', [ForumController::class, 'deleteReply'])->name('forum.delete.reply');
});
