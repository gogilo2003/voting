<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Position extends Model
{
    use HasFactory;

    /**
     * Get all of the candidates for the Position
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function totalVotes($electionId)
    {
        return Vote::where('position_id', $this->id)
            ->whereHas('candidate', function ($query) use ($electionId) {
                $query->where('election_id', $electionId);
            })
            ->count();
    }

    /**
     * The elections that belong to the Position
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function elections(): BelongsToMany
    {
        return $this->belongsToMany(Election::class);
    }
}
