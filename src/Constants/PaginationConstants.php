<?php

declare(strict_types=1);

namespace App\Constants;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaginationConstants
{
    private const DEFAULTS = [
        'items_per_page' => 10,
        'max_pages' => 100,
        'default_page' => 1,
        'page_range' => 2
    ];

    public function __construct(
        private readonly ParameterBagInterface $params
    ) {
    }

    private function getParam(string $key): mixed
    {
        try {
            return $this->params->get('app.pagination.' . $key);
        } catch (\Exception $e) {
            return self::DEFAULTS[$key] ?? null;
        }
    }

    public function getItemsPerPage(): int
    {
        return (int) $this->getParam('items_per_page');
    }

    public function getMaxPages(): int
    {
        return (int) $this->getParam('max_pages');
    }

    public function getDefaultPage(): int
    {
        return (int) $this->getParam('default_page');
    }

    public function getMaxPageSize(): int
    {
        return $this->getMaxPages();
    }

    public function getPageRange(): int
    {
        return (int) $this->getParam('page_range');
    }
} 