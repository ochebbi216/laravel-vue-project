<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class CheckInDate implements Rule
{
    public function passes($attribute, $value)
    {
        // Convert the input date to a Carbon instance for comparison
        $checkinDate = Carbon::createFromFormat('Y-m-d', $value);
        $today = Carbon::now();

        // Check if the checkin date is greater than or equal to today's date
        return $checkinDate->greaterThanOrEqualTo($today);
    }

    public function message()
    {
        return 'The checkin date must be greater than today.';
    }
}
