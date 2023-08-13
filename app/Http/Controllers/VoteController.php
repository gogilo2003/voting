<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Models\Vote;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreVoteRequest $request)
    {
        $message = '<ul class="list-outside ml-4 list-disc text-left space-y-3">';
        $type = 'success';
        $errors = 0;
        $success = 0;
        $vote = null;
        foreach ($request->votes as $value) {
            if (!$voted = Vote::with('position', 'candidate')
                ->where('user_id', $request->voter)
                ->where('election_id', $request->election)
                ->where('position_id', $value['position'])
                ->first()) {
                $vote = new Vote();

                $vote->user_id = $request->voter;
                $vote->election_id = $request->election;
                $vote->position_id = $value['position'];
                $vote->candidate_id = $value['candidate'];

                $vote->save();
                $message .= sprintf('<li>Your vote for <strong>%s</strong> in the <strong>%s</strong> position has been successfully recorded.</li>', $vote->candidate->user->name, $vote->position->name);
                $success++;
            } else {
                $errors++;
                $message .= sprintf('<li class="text-red-400">You have already voted for <strong>%s</strong> in the <strong>%s</strong> position</li>', $voted->candidate->user->name, $voted->position->name);
            }
        }

        $type = $errors === count($request->votes) ? 'danger' : ($success === count($request->votes) ? 'success' : 'warning');
        if ($success) {
            $message .= sprintf('</ul><p class="mt-6">Thank you for participating in the <strong>%s</strong>. Your contribution matters! 🗳️</p>', $vote->election->title);
        } else {
            $message .= '</ul>';
        }
        return redirect()->back()->with($type, $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoteRequest $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
