<?php

declare(strict_types=1);

namespace App\Constants;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CategoryConstants
{
    private const DEFAULTS = [
        'name.min_length' => 3,
        'name.max_length' => 50,
        'description.min_length' => 10,
        'description.max_length' => 500
    ];

    public function __construct(
        private readonly ParameterBagInterface $params
    ) {
    }

    private function getParam(string $key): mixed
    {
        try {
            return $this->params->get('app.category.' . $key);
        } catch (\Exception $e) {
            return self::DEFAULTS[$key] ?? null;
        }
    }

    public function getNameMinLength(): int
    {
        return (int) $this->getParam('name.min_length');
    }

    public function getNameMaxLength(): int
    {
        return (int) $this->getParam('name.max_length');
    }

    public function getDescriptionMinLength(): int
    {
        return (int) $this->getParam('description.min_length');
    }

    public function getDescriptionMaxLength(): int
    {
        return (int) $this->getParam('description.max_length');
    }
} 