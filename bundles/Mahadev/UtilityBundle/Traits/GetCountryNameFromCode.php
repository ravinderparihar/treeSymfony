<?php

namespace Mahadev\UtilityBundle\Traits;

use Symfony\Component\Intl\Countries;

trait GetCountryNameFromCode
{
    private const ALPHA3_TO_ALPHA2 = [
        'AFG' => 'AF', 'ALB' => 'AL', 'DZA' => 'DZ', 'AND' => 'AD', 'AGO' => 'AO',
        'ARG' => 'AR', 'ARM' => 'AM', 'AUS' => 'AU', 'AUT' => 'AT', 'AZE' => 'AZ',
        'BEL' => 'BE', 'BGR' => 'BG', 'BRA' => 'BR', 'CAN' => 'CA', 'CHE' => 'CH',
        'CHN' => 'CN', 'COL' => 'CO', 'CZE' => 'CZ', 'DEU' => 'DE', 'DNK' => 'DK',
        'ESP' => 'ES', 'EST' => 'EE', 'FIN' => 'FI', 'FRA' => 'FR', 'GBR' => 'GB',
        'GRC' => 'GR', 'HKG' => 'HK', 'HRV' => 'HR', 'HUN' => 'HU', 'IND' => 'IN',
        'IRL' => 'IE', 'ITA' => 'IT', 'JPN' => 'JP', 'KOR' => 'KR', 'LUX' => 'LU',
        'MEX' => 'MX', 'NLD' => 'NL', 'NOR' => 'NO', 'NZL' => 'NZ', 'POL' => 'PL',
        'PRT' => 'PT', 'RUS' => 'RU', 'SAU' => 'SA', 'SGP' => 'SG', 'SWE' => 'SE',
        'THA' => 'TH', 'TUR' => 'TR', 'USA' => 'US', 'ZAF' => 'ZA',
        // add more as needed
    ];

    public function getCountryName(string $code, string $locale = 'en'): ?string
    {
        $code = strtoupper($code);
        
        if(strlen($code) === 3) {
            $code = self::ALPHA3_TO_ALPHA2[$code];
        }

        // Direct 2-letter code
        if (Countries::exists($code)) {
            return Countries::getName($code, $locale);
        }

        return null; // Not found
    }
}


