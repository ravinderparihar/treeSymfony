<?php


namespace Mahadev\UtilityBundle\ResponseTransformer;

use DateTime;
use DateTimeZone;

trait DateTimeTrait
{
    public function getDateTimeFromString($value): ?\DateTime
    {
        try {
            return $value ? (new \DateTime($value))->setTimezone(new \DateTimeZone('UTC')) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getDateTimeFromProperty($propertyName): ?\DateTime
    {
        $value = $this->getPropertyValue($propertyName);
        return $this->getDateTimeFromString($value);
    }

    public function getStringFromDateTime(?\DateTime $value): ?string
    {
        return $value->format('Y-m-d\TH:i:s');
    }

    public function getFormatedDate(string $rawDate): string
    {
        $dt = DateTime::createFromFormat(
            'Y-m-d H:i:s', // matches "2025-10-07 12:34:47"
            $rawDate,
            new DateTimeZone('UTC')
        );

        if ($dt === false) {
            throw new \InvalidArgumentException("Invalid date: $rawDate");
        }

        // Return ISO 8601 format with milliseconds
        return $dt->format('Y-m-d\TH:i:s.642\Z');
    }
}
