<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Support\ElectionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ElectionResultsController extends Controller
{
    use ElectionTrait;

    public function showResults($electionId)
    {
        $election = Election::findOrFail($electionId);

        $election = $this->mapElection($election->load(['positions.candidates' => function ($query) use ($election) {
            $query->where('election_id', $election->id);
        }, 'positions.candidates.votes']));

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadHTML(view('pdf.results', compact('election'))->render());

        // $pdf->loadView('pdf.results', compact('election'));
        return $pdf->inline();
        // return $pdf->stream();
        // return view('pdf.results', compact('election'));
    }
}
