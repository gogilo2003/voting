<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Election extends Model
{
    use HasFactory;

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    protected $appends = ['total_voters'];

    /**
     * The positions that belong to the Election
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class);
    }

    /**
     * Get all of the candidates for the Election
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    /**
     * Get all of the votes for the Election
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    function getTotalVotersAttribute()
    {
        $uniqueVoters = Vote::where('election_id', $this->id)
            ->selectRaw('count(distinct user_id) as count')
            ->value('count');
        return $uniqueVoters;
    }
}
