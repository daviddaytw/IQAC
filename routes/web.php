<?php

use App\Http\Controllers\ContestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ScoreboardController;
use App\Http\Controllers\SubmissionController;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', [ContestController::class, 'index']);
Route::resource('contests', ContestController::class);
Route::resource('questions', QuestionController::class);
Route::resource('contests.submissions', SubmissionController::class);
Route::resource('contests.scoreboard', ScoreboardController::class);

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.redirect');
 
Route::get('/auth/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $avatarPath = "avatars/$googleUser->id.jpg";
    Storage::disk('public')->put($avatarPath, file_get_contents($googleUser->getAvatar()));

    $user = User::updateOrCreate([
        'google_id' => $googleUser->id,
    ], [
        'name' => $googleUser->name,
        'email' => $googleUser->email,
        'avatar' => Storage::url($avatarPath),
        'google_token' => $googleUser->token,
        'google_refresh_token' => $googleUser->refreshToken,
    ]);
 
    Auth::login($user);
 
    return redirect('/');
})->name('auth.callback');


Route::get('/auth/logout', function () {
    Auth::logout();
 
    return redirect('/');
})->name('auth.logout');
