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
    * @return Carbon[]
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

    /**
     * Method to remove the need to check whether a date is parseable or not, simplifying the handlers that are working
     * with dates.
     *
     * @param string|null $parseableDate
     * @return Carbon
     */
    public static function nowOrDate(string|null $parseableDate): Carbon
    {
        if (! $parseableDate) {
            return Carbon::now();
        }

        return Carbon::parse($parseableDate);
    }

    /**
     * A method designed to move date by a particular amount of Months, utility helper method to remove some conditional
     * logic from handlers.
     *
     * @param Carbon $date
     * @param string|null $direction
     * @param int $amount
     * @return Carbon
     */
    public static function moveDateByMonths(Carbon $date, ?string $direction, int $amount = 1): Carbon
    {
        if (! $direction) {
            return $date;
        }

        $method = $direction === 'left' ? 'subMonth' : 'addMonth';

        return $date->$method($amount);
    }
}