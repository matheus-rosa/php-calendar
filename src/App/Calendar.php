<?php

namespace Calendar\App;

use Calendar\Enum\ISO8601DayOfWeek;

class Calendar
{
    /**
     * @var Calendar
     */
    private static $staticInstance;

    /**
     * @var \DateTimeZone
     */
    private $timezone;

    /**
     * @var string|\DateTime
     */
    private $startDate;

    /**
     * @var string|\DateTime
     */
    private $endDate;

    public static function today()
    {
        return new \DateTime('now', self::getTimezone());
    }

    public static function tomorrow()
    {
        $todayDate = self::today();
        $todayDate->add(new \DateInterval('P1D'));

        return $todayDate;
    }

    public static function yesterday()
    {
        $todayDate = self::today();
        $todayDate->sub(new \DateInterval('P1D'));

        return $todayDate;
    }

    /**
     * @return Calendar
     * @throws \Exception
     */
    public static function now()
    {
        return self::interval(self::today());
    }

    /**
     * @param $date
     * @return Calendar
     * @throws \Exception
     */
    public static function fromDate($date)
    {
        return self::interval($date);
    }

    /**
     * @param string|\DateTime $startDate
     * @param string|\DateTime $endDate
     * @return Calendar
     * @throws \Exception
     */
    public static function interval($startDate, $endDate = '')
    {
        $calendarInstance = self::getCalendarInstance();
        if (!$startDate instanceof \DateTime) {
            $startDate = new \DateTime($startDate, self::getTimezone());
        }

        if (empty($endDate)) {
            $endDate = clone $startDate;
        } elseif (!$endDate instanceof \DateTime) {
            $endDate = new \DateTime($endDate, self::getTimezone());
        }

        // Trick to consider the informed last day, since \DatePeriod ignores it.
        $endDate->setTime(0, 0, 1);

        $calendarInstance->startDate = $startDate;
        $calendarInstance->endDate = $endDate;

        return $calendarInstance;
    }

    /**
     * @param $numberOfDays
     * @return \DatePeriod
     * @throws \Exception
     */
    public function beforeDays($numberOfDays)
    {
        $this->endDate = clone $this->startDate;
        $this->startDate->sub(new \DateInterval('P' . $numberOfDays . 'D'));

        return new \DatePeriod($this->startDate, new \DateInterval('P1D'), $this->endDate);
    }

    /**
     * @param $numberOfDays
     * @return \DatePeriod
     * @throws \Exception
     */
    public function nextDays($numberOfDays)
    {
        $this->endDate->add(new \DateInterval('P' . $numberOfDays . 'D'));
        $this->endDate->setTime(0, 0, 0);

        return new \DatePeriod($this->startDate, new \DateInterval('P1D'), $this->endDate);
    }

    public function onlyMondays()
    {
        return $this->getOnlyCustomDayOfWeek(ISO8601DayOfWeek::MONDAY);
    }

    public function onlyTuesdays()
    {
        return $this->getOnlyCustomDayOfWeek(ISO8601DayOfWeek::TUESDAY);
    }

    public function onlyWednesdays()
    {
        return $this->getOnlyCustomDayOfWeek(ISO8601DayOfWeek::WEDNESDAY);
    }

    public function onlyThursdays()
    {
        return $this->getOnlyCustomDayOfWeek(ISO8601DayOfWeek::THURSDAY);
    }

    public function onlyFridays()
    {
        return $this->getOnlyCustomDayOfWeek(ISO8601DayOfWeek::FRIDAY);
    }

    public function onlySaturdays()
    {
        return $this->getOnlyCustomDayOfWeek(ISO8601DayOfWeek::SATURDAY);
    }

    public function onlySundays()
    {
        return $this->getOnlyCustomDayOfWeek(ISO8601DayOfWeek::SUNDAY);
    }

    public function onlyFirstDayOfMonths()
    {
        $firstDayOfMonths = [];
        $datePeriod = new \DatePeriod($this->startDate, new \DateInterval('P1M'), $this->endDate);

        foreach ($datePeriod as $dateTime) {
            $firstDayOfMonths[] = new \DateTime($dateTime->format('Y-m-01'));
        }

        return $firstDayOfMonths;
    }

    public function onlyLastDayOfMonths()
    {
        $lastMonthDays = [];
        $datePeriod = new \DatePeriod($this->startDate, new \DateInterval('P1M'), $this->endDate);
        foreach ($datePeriod as $dateTime) {
            $lastMonthDays[] = new \DateTime($dateTime->format('Y-m-t'));
        }

        return $lastMonthDays;
    }

    public function nextMonday()
    {
        return $this->getDesiredNextDayOfWeek('monday');
    }

    public function nextTuesday()
    {
        return $this->getDesiredNextDayOfWeek('tuesday');
    }

    public function nextWednesday()
    {
        return $this->getDesiredNextDayOfWeek('wednesday');
    }

    public function nextThursday()
    {
        return $this->getDesiredNextDayOfWeek('thursday');
    }

    public function nextFriday()
    {
        return $this->getDesiredNextDayOfWeek('friday');
    }

    public function nextSaturday()
    {
        return $this->getDesiredNextDayOfWeek('saturday');
    }

    public function nextSunday()
    {
        return $this->getDesiredNextDayOfWeek('sunday');
    }

    public static function getTimezone()
    {
        if (is_null(self::$staticInstance)) {
            self::$staticInstance = new Calendar;
        }

        return (self::getCalendarInstance())->timezone ?: new \DateTimeZone(date_default_timezone_get());
    }

    public static function setTimezone($timezone)
    {
        $calendarInstance = self::getCalendarInstance();
        $calendarInstance->timezone = new \DateTimeZone($timezone);

        return $calendarInstance;
    }

    /**
     * @param $targetNumDayOfWeek
     * @return \DateTime[]
     */
    private function getOnlyCustomDayOfWeek($targetNumDayOfWeek)
    {
        $datePeriod = new \DatePeriod($this->startDate, new \DateInterval('P1D'), $this->endDate);
        $targetDayOfWeek = [];

        foreach ($datePeriod as $dateTime) {
            $ISO_dayOfWeek = $dateTime->format('N');
            if ((int) $ISO_dayOfWeek === $targetNumDayOfWeek) {
                $targetDayOfWeek[] = $dateTime;
            }
        }

        return $targetDayOfWeek;
    }

    private function getDesiredNextDayOfWeek($targetDay)
    {
        $allowedDaysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        if (in_array($targetDay, $allowedDaysOfWeek, true)) {
            $this->endDate->modify('next ' . $targetDay);
        }

        return $this->endDate;
    }

    private static function getCalendarInstance()
    {
        if (is_null(self::$staticInstance)) {
            self::$staticInstance = new Calendar;
        }

        return self::$staticInstance;
    }
}
