<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;

class ElectionResultsController extends Controller
{
    public function showResults($electionId)
    {
        $election = Election::findOrFail($electionId);

        $positions = $election->positions()->with([
            'candidates' => function ($query) {
                $query->orderBy('votes', 'desc'); // Order candidates by votes descending
            }
        ])->get();

        return view('election_results', compact('election', 'positions'));
    }
}
