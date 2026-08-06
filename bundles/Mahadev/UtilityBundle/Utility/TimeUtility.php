<?php


namespace Mahadev\UtilityBundle\Utility;


trait TimeUtility
{

    public static function getDateTimeFromMicrosecond($time): ?\DateTime
    {
        if(!$time) return null;
        $dateTime = new \DateTime();
        $dateTime->setTimestamp(floor($time/1000));
        return $dateTime;
    }

}