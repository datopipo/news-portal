<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\AppConstants;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppConstantsInitializer
{
    public function __construct(
        private readonly ParameterBagInterface $params
    ) {
        AppConstants::setParameterBag($this->params);
    }
} 