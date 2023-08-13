<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Election;
use App\Models\Candidate;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Support\ElectionTrait;

class CandidateController extends Controller
{
    use ElectionTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $elections = Election::orderBy('created_at', 'DESC')->get();

        $elections = $elections->map(function ($election) {
            return $this->mapElection(
                $election->load(['positions.candidates' => function ($query) use ($election) {
                    $query->where('election_id', $election->id);
                }])
            );
        });

        return Inertia::render('Candidates/Index', ['elections' => $elections]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidateRequest $request)
    {
        foreach ($request->candidates as $candidate_id) {

            $candidate = Candidate::where('user_id', $candidate_id)
                ->where('election_id', $request->election)
                ->where('position_id', $request->position)
                ->first() ?? new Candidate();

            $candidate->user_id = $candidate_id;
            $candidate->election_id = $request->election;
            $candidate->position_id = $request->position;

            $candidate->save();
        }

        return redirect()->back()->with('success', 'Candidates saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCandidateRequest $request, Candidate $candidate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return redirect()->back();
    }
}
