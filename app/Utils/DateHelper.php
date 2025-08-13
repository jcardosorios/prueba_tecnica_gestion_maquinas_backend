<?php

namespace App\Utils;

use Carbon\Carbon;


class DateHelper
{
    /**
     * Obtiene el siguiente dia habil.
     *
     * @param Carbon|string $date
     * @return Carbon
     */
    public static function getNextWorkingDay($date): Carbon
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $nextDay = $date->copy();

        do {
            $nextDay->addDay();
        } while ($nextDay->isWeekend());

        return $nextDay;
    }

    /**
     * Obtiene una hora aleatoria entre las 9:00 y las 14:00 con intervalos de 30 minutos.
     *
     * @return Carbon
     */
    public static function getRandomTime(): Carbon
    {
        $startHour = 9;
        $endHour = 14;
        
        $intervals = (($endHour - $startHour) * 60) / 30;
        $randomInterval = rand(0, $intervals);

        $randomMinutes = $startHour * 60 + ($randomInterval * 30);

        return Carbon::createFromTime(
            floor($randomMinutes / 60),
            $randomMinutes % 60,
            0
        );
    }


}