<?php

use Inertia\Inertia;
use App\Models\Election;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ElectionResultsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('results/{election}', [WelcomeController::class, 'results'])->name('results');
Route::get('/elections/{electionId}/results', [ElectionResultsController::class, 'showResults']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'first-login-completed',
])->group(function () {
    // Route::get('update-profile', function () {
    //     return Inertia::render('UpdateProfile');
    // })->name('update-profile');
    Route::prefix('dashboard')->name('dashboard')->group(function () {
        Route::get('', DashboardController::class);
        Route::post('vote', [VoteController::class, 'store'])->name('-vote');

        Route::prefix('elections')->name('-elections')->group(function () {
            Route::get('', [ElectionController::class, 'index']);
            Route::get('show/{election}', [ElectionController::class, 'show'])->name('-show');
            Route::patch('edit/{election}', [ElectionController::class, 'update'])->name('-update');
            Route::post('', [ElectionController::class, 'store'])->name('-store');
            Route::delete('{election}', [ElectionController::class, 'destroy'])->name('-delete');
            Route::patch('vacancies/', [ElectionController::class, 'vacancies'])->name('-vacancies');
        });

        Route::prefix('candidates')->name('-candidates')->group(function () {
            Route::get('', [CandidateController::class, 'index']);
            Route::patch('edit/{candidate}', [CandidateController::class, 'update'])->name('-update');
            Route::post('', [CandidateController::class, 'store'])->name('-store');
            Route::delete('delete/{candidate}', [CandidateController::class, 'destroy'])->name('-delete');
        });

        Route::prefix('positions')->name('-positions')->group(function () {
            Route::get('', [PositionController::class, 'index']);
            Route::patch('edit/{position}', [PositionController::class, 'update'])->name('-update');
            Route::post('', [PositionController::class, 'store'])->name('-store');
            Route::delete('{position}', [PositionController::class, 'destroy'])->name('-delete');
        });

        Route::prefix('members')->name('-members')->group(function () {
            Route::get('', [MemberController::class, 'index']);
            Route::patch('edit/{member}', [MemberController::class, 'update'])->name('-update');
            Route::post('', [MemberController::class, 'store'])->name('-store');
            Route::post('upload', [MemberController::class, 'upload'])->name('-upload');
            Route::post('photo', [MemberController::class, 'photo'])->name('-photo');
            Route::get('download', [MemberController::class, 'download'])->name('-download');
            Route::delete('{member}', [MemberController::class, 'destroy'])->name('-delete');
            Route::patch('password/{member}', [MemberController::class, 'password'])->name('-password');
            Route::patch('passwords', [MemberController::class, 'passwords'])->name('-passwords');
        });
    });
});
