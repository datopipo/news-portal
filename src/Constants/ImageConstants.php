<?php

declare(strict_types=1);

namespace App\Constants;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageConstants
{
    private const DEFAULTS = [
        'max_size' => '2M',
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif']
    ];

    public function __construct(
        private readonly ParameterBagInterface $params
    ) {
    }

    private function getParam(string $key): mixed
    {
        try {
            return $this->params->get('app.file.' . $key);
        } catch (\Exception $e) {
            return self::DEFAULTS[$key] ?? null;
        }
    }

    public function getMaxImageSize(): string
    {
        return (string) $this->getParam('max_size');
    }

    public function getAllowedImageTypes(): array
    {
        return (array) $this->getParam('allowed_types');
    }
} 