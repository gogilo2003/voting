<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $table = 'users';

    /**
     * Get all of the candidates for the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'user_id', 'id');
    }

    /**
     * Interact with the member's phone.
     */
    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value,
            set: function (string $value) {
                // if ($value[0] == '0') {
                try {
                    $phone = new PhoneNumber($value, 'KE');
                    return $phone->formatE164();
                } catch (\Throwable $th) {
                    return $value;
                }
                // }
            }
        );
    }
}
