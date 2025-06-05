<?php

declare(strict_types=1);

namespace App\Constants;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NewsConstants
{
    private const DEFAULTS = [
        'recent_limit' => 3,
        'top_limit' => 10
    ];

    public function __construct(
        private readonly ParameterBagInterface $params
    ) {
    }

    private function getParam(string $key): mixed
    {
        try {
            return $this->params->get('app.news.' . $key);
        } catch (\Exception $e) {
            return self::DEFAULTS[$key] ?? null;
        }
    }

    public function getRecentNewsLimit(): int
    {
        return (int) $this->getParam('recent_limit');
    }

    public function getTopNewsLimit(): int
    {
        return (int) $this->getParam('top_limit');
    }
} 