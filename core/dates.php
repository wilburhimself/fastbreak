<?php

namespace Core;

class Dates
{
    const HOUR = 3600;
    const DAY = 86400;
    const WEEK = 604800;


    private int $timestamp;

    public function __construct(?int $timestamp = null)
    {
        $this->timestamp = $timestamp ?? time();
    }

    public function get_day(): int
    {
        return mktime(0, 0, 0, date('m', $this->timestamp), date('d', $this->timestamp), date('Y', $this->timestamp));
    }

    public function get_timestamp(): int
    {
        return $this->timestamp;
    }

    public function get_date_from_week(): int
    {
        $year = date('Y', $this->timestamp);
        $firstDayOfYear = strtotime("{$year}-01-01");
        $isMonday = date('w', $firstDayOfYear) == 1;
        $isWeekOne = strftime('%V', $firstDayOfYear) == 1;

        $weekOneStart = $isWeekOne
            ? ($isMonday ? strtotime('last monday', $firstDayOfYear) : $firstDayOfYear)
            : strtotime('next monday', $firstDayOfYear);

        $weekNumber = date('W', $this->timestamp);
        $this->timestamp = $weekOneStart + (self::WEEK * ($weekNumber - 1));

        return $this->timestamp;
    }

    public function remove_day(): void
    {
        $this->timestamp -= self::DAY;
    }
    public function add_day(): void
    {
        $this->timestamp += self::DAY;
    }

    public function __toString(): string
    {
        return (string)$this->timestamp;
    }
}