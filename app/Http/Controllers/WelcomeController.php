<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Election;
use Illuminate\Http\Request;
use App\Support\ElectionTrait;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class WelcomeController extends Controller
{
    use ElectionTrait;
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $election = Election::where('start_at', '<', now())
            ->orderBy('created_at', 'DESC')
            ->first();

        $election = $this->mapElection($election->load(['positions.candidates' => function ($query) use ($election) {
            $query->where('election_id', $election->id);
        }, 'positions.candidates.votes']));

        return Inertia::render('Result', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'election' => $election
        ]);
    }

    function results(Election $election)
    {
        $election->load(['positions.candidates' => function ($query) use ($election) {
            $query->where('election_id', $election->id);
        }]);

        $election = $this->mapElection($election);

        return Inertia::render('Result', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'election' => $election
        ]);
    }
}
