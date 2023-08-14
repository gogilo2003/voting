<?php

namespace App\Support;

use App\Models\Member;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;

trait ElectionTrait
{
    function mapElection(Election $election): object
    {
        $el = [
            "id" => $election->id,
            "title" => $election->title,
            "description" => $election->description,
            "start" => $election->start_at->isoFormat('lll'),
            "end" => $election->end_at->isoFormat('lll'),
            "status" => $election->end_at >= now() ? "Active" : "Closed",
            "total_voted" => $election->total_voters,
            "total_voters" => Member::all()->count() - env('MINUS_VOTERS'),
        ];

        if ($election->relationLoaded('positions')) {
            $el["positions"] = $election->positions->map(fn ($item) => $this->mapPosition($item, $election->id));
        }

        return (object)$el;
    }

    function mapPosition(Position $position, $election_id = null): object
    {
        $pos = [
            "id" => $position->id,
            "name" => $position->name,
            "description" => $position->description,
            "total_votes" => $position->totalVotes($election_id),
        ];

        if ($position->relationLoaded('candidates')) {
            $pos['candidates'] = $position->candidates->map(
                fn ($item) => $this->mapCandidate($item, $election_id, $position->id)
            );
        }

        return (object)$pos;
    }

    function mapCandidate(Candidate $candidate, $election_id, $position_id): object
    {
        $c = [
            "id" => $candidate->id,
            "name" => $candidate->user->name,
            "phone" => $candidate->user->phone,
            "photo" => $candidate->user->profile_photo_url,
            "selected" => false,
            "vote_count" => $candidate->voteCount($election_id, $position_id)
        ];

        // if ($candidate->relationLoaded('votes')) {
        //     $c['votes'] = $candidate->votes;
        // }

        return (object)$c;
    }
    function mapMember(Member $member): object
    {
        return (object)[
            "id" => $member->id,
            "name" => $member->name,
            "phone" => $member->phone,
            "email" => $member->email,
            "photo" => $member->profile_photo_url,
        ];
    }
}
