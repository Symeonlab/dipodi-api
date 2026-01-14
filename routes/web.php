<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Models\UserProgress; // <-- Add this import at the top
use App\Models\Post; // <-- Add this import at the top

Route::get('/dashboard', function () {
    // Get stats for the currently logged-in user
    $user = auth()->user();
    $myProgressCount = UserProgress::where('user_id', $user->id)->count();
    $latestPost = Post::where('is_published', true)->latest()->first();
    $myLatestWeight = UserProgress::where('user_id', $user->id)
        ->whereNotNull('weight')
        ->latest('date')
        ->first();

    return view('dashboard', [
        'myProgressCount' => $myProgressCount,
        'latestPost' => $latestPost,
        'myLatestWeight' => $myLatestWeight,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
