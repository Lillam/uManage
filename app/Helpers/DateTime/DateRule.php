<?php

namespace App\Helpers\DateTime;

use Exception;
use DateInterval;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DateRule
{
    /** @var Collection */
    protected Collection $recurrences;

    /** @var int */
    protected int $count = 0;

    /** @var Carbon */
    protected Carbon $startDate;

    /** @var Carbon */
    protected Carbon $endDate;

    /** @var array|string[] */
    private array $incrementalMethods = [
        'DAILY'   => 'addDays',
        'WEEKLY'  => 'addWeeks',
        'MONTHLY' => 'addMonths',
        'YEARLY'  => 'addYears'
    ];

    /** @var array|string[] */
    private array $decrementalMethods = [
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
        if (! array_key_exists($frequency = mb_strtoupper($frequency), $this->incrementalMethods))
            throw new Exception('Incorrect use of frequency, try using: daily, weekly, monthly or yearly');

        $this->frequency = $frequency;
        return $this;
    }

    /**
    * @param Carbon|string $startDate  This is the starting date of which the schedule will be built up around; the
    *                                  starting point will be incremented on via (x) days, weeks, months, years.
    * @return $this
    */
    public function setStartDate(Carbon|string $startDate): self
    {
        $this->startDate = ! $startDate instanceof Carbon ? Carbon::parse($startDate) : $startDate;

        return $this;
    }

    /**
    * @param Carbon|string $endDate  This is the ending date of which the schedule will be ending on, this will always
    *                                be set, the end date will always be what the last date was made around.
    * @return $this
    */
    public function setEndDate(Carbon|string $endDate): self
    {
        $this->endDate = ! $endDate instanceof Carbon ? Carbon::parse($endDate) : $endDate;

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
            ? $this->incrementalMethods[$this->frequency]
            : $this->decrementalMethods[$this->frequency];
    }

    /**
    * @return self
    */
    public function create(): self
    {
        for ($i = 0; $i < $this->count; $i++) {
            $recurrence = Carbon::parse($this->startDate)->{$this->getDateAlteringMethod()}($i);

            // if we have hit the end date, regardless of how many times the user has wanted to keep the rule going for
            // then we are looking to break the loop here and no longer continue attempting to make anymore.
            if (isset($this->endDate) && $recurrence > $this->endDate)
                break;

            // when we are making recurring dates, we are going to append a new DateRuleRecurrence into a collection
            // and begin constructing a variety of useful foundations that a DateRuleRecurrence will need in order for
            // building a schedule.
            $this->recurrences->put($i, new DateRuleRecurrence(
                $recurrence,
                $this->startDate->diff($recurrence)
            ));
        }

        // if we don't have an end date set already, then we are going to add the last recurrence that was entered into
        // the array... so that we can have a solid stopping point.
        if (! isset($this->endDate))
            $this->setEndDate($this->recurrences->last()->date);

        $this->difference = $this->startDate->diff($this->endDate);

        return $this;
    }
}