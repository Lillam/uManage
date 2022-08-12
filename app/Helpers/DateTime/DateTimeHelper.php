<?php

namespace App\Helpers\DateTime;

use Carbon\Carbon;

class DateTimeHelper
{
    const FORMAT_Daynth_M_Y = 'jS F Y H:i';

    /**
    * This method is a fast way of just returning the full week, keyed by the days, followed by the date being returned
    * as a carbon instance.
    *
    * @param Carbon $startingDay
    * @return array
    */
    public static function days(Carbon $startingDay): array
    {
        return [
            'monday'    => $startingDay->copy()->startOfWeek(),
            'tuesday'   => $startingDay->copy()->addDay(1),
            'wednesday' => $startingDay->copy()->addDay(2),
            'thursday'  => $startingDay->copy()->addDay(3),
            'friday'    => $startingDay->copy()->addDay(4),
            'saturday'  => $startingDay->copy()->addDay(5),
            'sunday'    => $startingDay->copy()->addDay(6)
        ];
    }
}