<?php

use PHPUnit\Framework\TestCase;
use Calendar\App\Calendar;

class CalendarTest extends TestCase
{
    public function testTodayIsDateTimeInstance()
    {
        $this->assertInstanceOf(\DateTime::class, Calendar::today(), 'Not a \DateTime instance.');
    }

    public function testYesterdayIsDateTimeInstance()
    {
        $this->assertInstanceOf(\DateTime::class, Calendar::yesterday(), 'Not a \DateTime instance.');
    }

    public function testTomorrowIsDateTimeInstance()
    {
        $this->assertInstanceOf(\DateTime::class, Calendar::tomorrow(), 'Not a \DateTime instance.');
    }

    public function testNextMonday()
    {
        $nextMonday = Calendar::fromDate('2020-05-26')->nextMonday();
        $expectedMonday = new \DateTime('2020-06-01');

        $this->assertEquals($expectedMonday, $nextMonday, 'The result next monday date is not a monday.');
    }

    public function testNextTuesday()
    {
        $nextTuesday = Calendar::fromDate('2020-05-26')->nextTuesday();
        $expectedTuesday = new \DateTime('2020-06-02');

        $this->assertEquals($expectedTuesday, $nextTuesday, 'The result next tuesday date is not a tuesday.');
    }

    public function testNextWednesday()
    {
        $nextWednesday = Calendar::fromDate('2020-05-26')->nextWednesday();
        $expectedWednesday = new \DateTime('2020-05-27');

        $this->assertEquals($nextWednesday, $expectedWednesday, 'The result next wednesday date is not a wednesday.');
    }

    public function testNextThursday()
    {
        $nextThursday = Calendar::fromDate('2020-05-26')->nextThursday();
        $expectedThursday = new \DateTime('2020-05-28');

        $this->assertEquals($expectedThursday, $nextThursday, 'The result next thursday date is not a thursday.');
    }

    public function testNextFriday()
    {
        $nextFriday = Calendar::fromDate('2020-05-26')->nextFriday();
        $expectedFriday = new \DateTime('2020-05-29');

        $this->assertEquals($expectedFriday, $nextFriday, 'The result next friday date is not a friday.');
    }

    public function testNextSaturday()
    {
        $nextSaturday = Calendar::fromDate('2020-05-26')->nextSaturday();
        $expectedSaturday = new \DateTime('2020-05-30');

        $this->assertEquals($expectedSaturday, $nextSaturday, 'The result next saturday date is not a saturday.');
    }

    public function testNextSunday()
    {
        $nextSunday = Calendar::fromDate('2020-05-26')->nextSunday();
        $expectedSunday = new \DateTime('2020-05-31');

        $this->assertEquals($expectedSunday, $nextSunday, 'The result next sunday date is not a sunday.');
    }

    public function testOnlyMondaysByInterval()
    {
        $onlyMondays = Calendar::interval('2020-05-01', '2020-05-31')->onlyMondays();
        $expectedMondaysDate = ['2020-05-04', '2020-05-11', '2020-05-18', '2020-05-25'];
        $this->__testIntervalDaysOfWeek($expectedMondaysDate, $onlyMondays, 'The result dates does not match with the expected mondays.');
    }

    public function testOnlyTuesdaysByInterval()
    {
        $onlyTuesdays = Calendar::interval('2020-05-01', '2020-05-31')->onlyTuesdays();
        $expectedTuesdaysDate = ['2020-05-05', '2020-05-12', '2020-05-19', '2020-05-26'];
        $this->__testIntervalDaysOfWeek($expectedTuesdaysDate, $onlyTuesdays, 'The result dates does not match with the expected tuesdays.');
    }

    public function testNextDays()
    {
        $nextDays = Calendar::fromDate('2020-05-01')->nextDays(10);
        $expectedNextDays = new DatePeriod(new DateTime('2020-05-01'), new DateInterval('P1D'), new DateTime('2020-05-11'));
        $this->assertEquals($expectedNextDays, $nextDays);
    }

    private function __testIntervalDaysOfWeek($expectedDates, $daysOfWeek, $message)
    {
        $expectedDaysOfWeek = [];
        foreach ($expectedDates as $date) {
            $expectedDaysOfWeek[] = new \DateTime($date);
        }

        $this->assertEquals($expectedDaysOfWeek, $daysOfWeek, $message);
    }
}
