<?php

namespace App\Serializer;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function decode(string $data, string $format, array $context = []): ?array
    {
        $request = $this->requestStack->getCurrentRequest();

        return array_map(
            static function ($element) {
                $decoded = json_decode($element, true);

                return json_last_error() === JSON_ERROR_NONE ? $decoded : $element;
            },
            array_replace_recursive(
                $request->request->all(),
                $request->files->all()
            )
        );
    }

    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            self::FORMAT => true,
        ];
    }
}