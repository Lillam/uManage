<?php

namespace App\Helpers\DateTime;

use DateInterval;
use Carbon\Carbon;

class DateRuleRecurrence
{
    public function __construct(
        public Carbon $date,
        public DateInterval $difference
    ) { }
}