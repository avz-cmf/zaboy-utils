<?php

namespace zaboy\utils;

class UtcTime
{

    const WITHOUT_MICROSECONDS = 0;
    const WITH_TENTHS = 1; //0.1
    const WITH_HUNDREDTHS = 2; //0.01

    /**
     *
     * @param type $precision
     * @return int|double
     */

    public static function getUtcTimestamp($precision = self::WITHOUT_MICROSECONDS)
    {
        return round(microtime(1) - date('Z'), $precision);
    }

}
