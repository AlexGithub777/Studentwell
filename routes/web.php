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
use App\Http\Controllers\HealthInsightsController;

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
Route::get('/signup', [UserController::class, 'showSignupForm'])->name('signup');
Route::get('/signin', [UserController::class, 'showSigninForm'])->name('signin');
Route::post('/signup', [UserController::class, 'signup'])->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/signin', [UserController::class, 'signin'])->name('login');


// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Account Routes
    Route::prefix('account')->as('account.')->group(function () {
        Route::get('/', [UserController::class, 'showAccount'])->name('show');
        Route::get('/edit', [UserController::class, 'editAccountPage'])->name('edit');
        Route::put('/edit', [UserController::class, 'updateAccount'])->name('update');
    });

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
    Route::prefix('mood')->as('mood.')->group(function () {
        Route::get('/', [MoodTrackingController::class, 'index'])->name('index');
        Route::get('/track-mood', [MoodTrackingController::class, 'track'])->name('track');
        Route::post('/track-mood', [MoodTrackingController::class, 'store'])->name('store');
        Route::get('/edit-mood/{id}', [MoodTrackingController::class, 'edit'])->name('edit');
        Route::put('/edit-mood/{id}', [MoodTrackingController::class, 'update'])->name('update');
    });

    // Exercise Routes
    Route::prefix('exercise')->group(function () {
        Route::get('/', [ExerciseController::class, 'index'])->name('exercise.index');
        Route::get('/plan-exercise', [ExerciseController::class, 'addPlannedExercisePage'])->name('exercise.plan');
        Route::post('/plan-exercise', [ExerciseController::class, 'storePlannedExercise'])->name('exercise.store.plan');
        Route::get('/log-exercise/{plannedExerciseID}', [ExerciseController::class, 'logExercisePage'])->name('exercise.log');
        Route::get('/log-exercise', [ExerciseController::class, 'logExercisePage'])->name('exercise.log.unplanned');
        Route::post('/log-exercise/{plannedExerciseID?}', [ExerciseController::class, 'storeLoggedExercise'])->name('exercise.store.log');
        Route::get('/edit-exercise/{plannedExerciseID}', [ExerciseController::class, 'editPlannedExercisePage'])->name('exercise.edit');
        Route::put('/edit-exercise/{plannedExerciseID}', [ExerciseController::class, 'updatePlannedExercise'])->name('exercise.update');
    });

    // Goals Routes
    Route::prefix('goals')->group(function () {
        Route::get('/', [GoalSettingController::class, 'index'])->name('goals.index');
        Route::get('/log-goal/{id}', [GoalSettingController::class, 'log'])->name('goals.log');
        Route::post('/log-goal/{id}', [GoalSettingController::class, 'storeLog'])->name('goals.store.log');
        Route::get('/edit-goal/{id}', [GoalSettingController::class, 'edit'])->name('goals.edit');
        Route::put('/edit-goal/{id}', [GoalSettingController::class, 'update'])->name('goals.update');
        Route::get('/set-goal', [GoalSettingController::class, 'set'])->name('goals.set');
        Route::post('/set-goal', [GoalSettingController::class, 'store'])->name('goals.store.set');
    });

    // Sleep Routes
    Route::prefix('sleep')->group(function () {
        Route::get('/', [SleepLogController::class, 'index'])->name('sleep.index');
        Route::get('/log-sleep', [SleepLogController::class, 'log'])->name('sleep.log');
        Route::post('/log-sleep', [SleepLogController::class, 'store'])->name('sleep.store');
        Route::get('/edit-sleep/{id}', [SleepLogController::class, 'edit'])->name('sleep.edit');
        Route::put('/edit-sleep/{id}', [SleepLogController::class, 'update'])->name('sleep.update');
    });

    // Health Insights Routes
    Route::prefix('health-insights')->group(function () {
        Route::get('/', [HealthInsightsController::class, 'index'])->name('health-insights.index');
        Route::get('/mood', [HealthInsightsController::class, 'mood'])->name('health-insights.mood');
        Route::get('/exercise', [HealthInsightsController::class, 'exercise'])->name('health-insights.exercise');
        Route::get('/sleep', [HealthInsightsController::class, 'sleep'])->name('health-insights.sleep');
        Route::get('/goals', [HealthInsightsController::class, 'goals'])->name('health-insights.goals');
    });

    // Forum Routes
    Route::prefix('forum')->group(function () {
        Route::get('/', [ForumController::class, 'index'])->name('forum.index');
        Route::get('/create', [ForumController::class, 'create'])->name('forum.create');
        Route::post('/', [ForumController::class, 'store'])->name('forum.store');
        Route::get('/post/{id}', [ForumController::class, 'show'])->name('forum.show');
        Route::post('/post/{id}/reply', [ForumController::class, 'reply'])->name('forum.reply');
        Route::post('/post/{forum_post}/like', [ForumController::class, 'likePost'])->name('forum.like.post');
        Route::post('/reply/{forum_reply}/like', [ForumController::class, 'likeReply'])->name('forum.like.reply');
        Route::delete('/post/{id}', [ForumController::class, 'delete'])->name('forum.delete');
        Route::delete('/reply/{id}', [ForumController::class, 'deleteReply'])->name('forum.delete.reply');
    });
});
