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
    * @return array
    */
    public static function days(Carbon $starting_day): array
    {
        return [
            'monday'    => $starting_day->copy()->startOfWeek(),
            'tuesday'   => $starting_day->copy()->addDay(1),
            'wednesday' => $starting_day->copy()->addDay(2),
            'thursday'  => $starting_day->copy()->addDay(3),
            'friday'    => $starting_day->copy()->addDay(4),
            'saturday'  => $starting_day->copy()->addDay(5),
            'sunday'    => $starting_day->copy()->addDay(6)
        ];
    }
}