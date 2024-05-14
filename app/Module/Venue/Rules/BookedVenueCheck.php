<?php

namespace App\VenueModule\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Venue;
use Carbon\Carbon;// Assuming Venue model exists

class BookedVenueCheck implements Rule
{
    public function passes($attribute, $value): bool
    {
        $venue = Venue::where('name', $value)->first();

        if (!$venue) {
            // Venue not found, so validation fails
            return false;
        }

        // Check if the venue's booked date is within 5 days from now
        $fiveDaysFromNow = Carbon::now()->addDays(5);
        if ($venue->booked_date && $venue->booked_date->lte($fiveDaysFromNow)) {
            // Venue's booked date is within 5 days from now, so validation fails
            return false;
        }

        // Validation passes
        return true;
    }

    public function message(): string
    {
        return 'The selected :attribute is invalid because the venue is booked within 5 days from now.';
    }
}



