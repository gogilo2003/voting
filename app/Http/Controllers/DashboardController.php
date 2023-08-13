<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Election;
use Illuminate\Http\Request;
use App\Support\ElectionTrait;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class DashboardController extends Controller
{
    use ElectionTrait;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $election = Election::where('start_at', '<', now())
            ->where('end_at', '>', now())
            ->orderBy('created_at', 'DESC')->first();

        $election = $election ? $this->mapElection($election->load(['positions.candidates' => function ($query) use ($election) {
            $query->where('election_id', $election->id);
        }, 'positions.candidates.votes'])) : null;

        $elections = $election ? null : Election::orderBy('created_at', 'DESC')
            ->where('end_at', '<', now())
            ->get();

        return Inertia::render('Dashboard', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'election' => $election,
            'elections' => $elections,
        ]);
    }
}
