<?php

declare(strict_types=1);

namespace App\Constants;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppConstants
{
    private static ?ParameterBagInterface $params = null;

    public static function setParameterBag(ParameterBagInterface $params): void
    {
        self::$params = $params;
    }

    private static function getParam(string $key): mixed
    {
        if (self::$params === null) {
            throw new \RuntimeException('ParameterBag not set. Call setParameterBag() first.');
        }

        try {
            return self::$params->get('app.' . $key);
        } catch (\Exception $e) {
            // Fallback to default values if parameter is not set
            return self::getDefaultValue($key);
        }
    }

    private static function getDefaultValue(string $key): mixed
    {
        // Basic fallback values for legacy support
        // Most functionality has been moved to dedicated constants classes
        $defaults = [];

        return $defaults[$key] ?? null;
    }
}
