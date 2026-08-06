<?php


namespace Mahadev\UtilityBundle\Utility;


class BasicCalculator
{

    public static function addPercentToAmount($amount, $percent){
        return $amount * ( (100 + $percent) * 0.01);
    }

    public static function getTaxAmountFromPercent($amount, $percent){
        return $amount * $percent * 0.01;
    }

    public static function getAmountFromPercent($amount, $percent){
        return $amount * $percent * 0.01;
    }

}