<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\PaginationConstants;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class PaginationService
{
    public function __construct(
        private readonly PaginationConstants $paginationConstants
    ) {
    }

    public function getPaginationParameters(Request $request): array
    {
        $page = (int) $request->query->get('page', $this->paginationConstants->getDefaultPage());
        $limit = (int) $request->query->get('limit', $this->paginationConstants->getItemsPerPage());

        // Ensure limit doesn't exceed maximum
        $limit = min($limit, $this->paginationConstants->getMaxPageSize());

        return [
            'page' => max(1, $page), // Ensure page is at least 1
            'limit' => $limit
        ];
    }

    public function paginate(QueryBuilder $queryBuilder, Request $request): array
    {
        $params = $this->getPaginationParameters($request);
        $page = $params['page'];
        $limit = $params['limit'];
        
        // Calculate offset
        $offset = ($page - 1) * $limit;
        
        // Get total count
        $countQueryBuilder = clone $queryBuilder;
        $countQueryBuilder->select('COUNT(DISTINCT ' . $countQueryBuilder->getRootAliases()[0] . '.id)');
        $totalItems = (int) $countQueryBuilder->getQuery()->getSingleScalarResult();
        
        // Apply pagination to main query
        $queryBuilder->setFirstResult($offset)
                    ->setMaxResults($limit);
        
        // Get paginated results
        $items = $queryBuilder->getQuery()->getResult();
        
        // Calculate total pages
        $totalPages = (int) ceil($totalItems / $limit);
        
        return [
            'items' => $items,
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'items_per_page' => $limit,
            'has_next_page' => $page < $totalPages,
            'has_previous_page' => $page > 1
        ];
    }
} 