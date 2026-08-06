<?php


namespace Mahadev\UtilityBundle\Utility;


class ChecksumCorrector
{

    public static function generateCheckdigit($upc_code, $length)
    {
        $odd_total  = 0;
        $even_total = 0;

        for($i=0; $i<$length; $i++)
        {
            if((($i+1)%2) == 0) {
                /* Sum even digits */
                $even_total += $upc_code[$i];
            } else {
                /* Sum odd digits */
                $odd_total += $upc_code[$i];
            }
        }

        $sum = (3 * $odd_total) + $even_total;

        /* Get the remainder MOD 10*/
        $check_digit = $sum % 10;

        /* If the result is not zero, subtract the result from ten. */
        $finalCheck = ($check_digit > 0) ? 10 - $check_digit : $check_digit;

        return $upc_code.$finalCheck;
    }

}
