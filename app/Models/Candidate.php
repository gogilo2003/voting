<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the position that owns the Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the election that owns the Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get all of the votes for the Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function voteCount($electionId, $positionId)
    {
        return $this->hasMany(Vote::class)
            ->whereHas('position', function ($query) use ($electionId, $positionId) {
                $query->where('election_id', $electionId)
                    ->where('id', $positionId);
            })
            ->count();
    }
}
