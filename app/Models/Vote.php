<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;
    /**
     * Get the candidate that owns the Vote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the position that owns the Vote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the election that owns the Vote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
}
