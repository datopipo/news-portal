<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\AppConstants;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AppConstantsInitializer
{
    public function __construct(
        private readonly ParameterBagInterface $params
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        AppConstants::setParameterBag($this->params);
    }
}
