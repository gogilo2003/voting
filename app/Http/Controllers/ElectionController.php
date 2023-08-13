<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Election;
use App\Models\Position;
use App\Support\ElectionTrait;
use App\Http\Requests\StoreElectionRequest;
use App\Http\Requests\UpdateElectionRequest;
use App\Http\Requests\UpdateVacanciesRequest;
use App\Models\Member;

class ElectionController extends Controller
{
    use ElectionTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Elections/Index', [
            'elections' => Election::with('positions')->get()->map(fn ($item) => $this->mapElection($item)),
            'positions' => Position::all()->map(fn ($item) => $this->mapPosition($item))
        ]);
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
    public function store(StoreElectionRequest $request)
    {
        $election = new Election();
        $election->title = $request->title;
        $election->description = $request->description;
        $election->start_at = $request->start;
        $election->end_at = $request->end;
        $election->save();
        $election->positions()->sync($request->positions);
        return redirect()->back()->with('success', 'Election Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Election $election)
    {
        $positions = Position::all();

        $election->load(['positions.candidates' => function ($query) use ($election) {
            $query->where('election_id', $election->id);
        }]);

        return Inertia::render('Elections/Show', [
            'election' => $this->mapElection($election),
            'positions' => $positions->map(fn ($item) => $this->mapPosition($item)),
            'members' => Member::where('is_admin', 0)->get()->map(fn ($item) => $this->mapMember($item))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function vacancies(UpdateVacanciesRequest $request)
    {
        $election = Election::find($request->id);

        $election->positions()->sync($request->positions);
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateElectionRequest $request, Election $election)
    {
        $election->title = $request->title;
        $election->description = $request->description;
        $election->start_at = $request->start;
        $election->end_at = $request->end;
        $election->save();
        $election->positions()->sync($request->positions);
        return redirect()->back()->with('success', 'Election updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Election $election)
    {
        //
    }
}
