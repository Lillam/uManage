<?php

namespace App\Helpers\DateTime;

use Exception;
use DateInterval;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Helpers\DateTime\DateRuleRecurrence;

class DateRule
{
    /** @var Collection */
    protected Collection $recurrences;

    /** @var int */
    protected int $count = 0;

    /** @var Carbon */
    protected Carbon $start_date;

    /** @var Carbon */
    protected Carbon $end_date;

    /** @var array|string[] */
    private array $incremental_methods = [
        'DAILY'   => 'addDays',
        'WEEKLY'  => 'addWeeks',
        'MONTHLY' => 'addMonths',
        'YEARLY'  => 'addYears'
    ];

    /** @var array|string[] */
    private array $decremental_methods = [
        'DAILY'   => 'subDays',
        'WEEKLY'  => 'subWeeks',
        'MONTHLY' => 'subMonths',
        'YEARLY'  => 'subYears'
    ];

    /** @var DateInterval */
    protected DateInterval $difference;

    /** @var bool */
    protected bool $invert = false;

    /** @var string */
    protected string $frequency = 'DAILY';

    /** @var string */
    protected string $timezone;

    /** DateRule constructor. */
    public function __construct()
    {
        $this->recurrences = collect();
    }

    /**
    * @param int $count  The number of dates that we're going to be needing to make, the interval because of this count
    *                    will always be a number behind, because it's taking this year/day/month/week into consideration
    * @return $this
    */
    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    /**
    * @param string $frequency  This will want to be either: daily, weekly, monthly or yearly, anything else will result
    *                           in error; without setting one, the default will be daily.
    * @return $this
    * @throws Exception
    */
    public function setFrequency(string $frequency): self
    {
        if (! array_key_exists($frequency = mb_strtoupper($frequency), $this->incremental_methods))
            throw new Exception('Incorrect use of frequency, try using: daily, weekly, monthly or yearly');

        $this->frequency = $frequency;
        return $this;
    }

    /**
    * @param Carbon|string $start_date  This is the starting date of which the schedule will be built up around; the
    *                                   starting point will be incremented on via (x) days, weeks, months, years.
    * @return $this
    */
    public function setStartDate(Carbon|string $start_date): self
    {
        $this->start_date = ! $start_date instanceof Carbon ? Carbon::parse($start_date) : $start_date;
        return $this;
    }

    /**
    * @param Carbon|string $end_date  This is the ending date of which the schedule will be ending on, this will always
    *                                 be set, the end date will always be what the last date was made around.
    * @return $this
    */
    public function setEndDate(Carbon|string $end_date): self
    {
        $this->end_date = ! $end_date instanceof Carbon ? Carbon::parse($end_date) : $end_date;
        return $this;
    }

    /**
    * @param bool $invert  This is a setting to decide which way the schedule is going to be working, if this is set to
    *                      true, then this will subtract from the day as opposed to add to it, and reverse the whole
    *                      process of building dates.
    * @return $this
    */
    public function setInvert(bool $invert): self
    {
        $this->invert = $invert;
        return $this;
    }

    /**
    * @return Collection
    */
    public function getRecurrences(): Collection
    {
        return $this->recurrences;
    }

    /**
    * @return string
    */
    private function getDateAlteringMethod(): string
    {
        return $this->invert === false
            ? $this->incremental_methods[$this->frequency]
            : $this->decremental_methods[$this->frequency];
    }

    /**
    * @return self
    */
    public function create(): self
    {
        for ($i = 0; $i < $this->count; $i++) {
            $recurrence = Carbon::parse($this->start_date)->{$this->getDateAlteringMethod()}($i);

            // if we have hit the end date, regardless of how many times the user has wanted to keep the rule going for
            // then we are looking to break the loop here and no longer continue attempting to make anymore.
            if (isset($this->end_date) && $recurrence > $this->end_date)
                break;

            // when we are making recurring dates, we are going to append a new DateRuleRecurrence into a collection
            // and begin constructing a variety of useful foundations that a DateRuleRecurrence will need in order for
            // building a schedule.
            $this->recurrences->put($i, new DateRuleRecurrence(
                $recurrence,
                $this->start_date->diff($recurrence)
            ));
        }

        // if we don't have an end date set already, then we are going to add the last recurrence that was entered into
        // the array... so that we can have a solid stopping point.
        if (! isset($this->end_date))
            $this->setEndDate($this->recurrences->last()->date);

        $this->difference = $this->start_date->diff($this->end_date);

        return $this;
    }
}