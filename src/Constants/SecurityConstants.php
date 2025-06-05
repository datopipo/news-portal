<?php

declare(strict_types=1);

namespace App\Constants;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SecurityConstants
{
    private const DEFAULTS = [
        'password.min_length' => 8,
        'password.max_length' => 4096,
        'login.max_attempts' => 3,
        'login.lockout_time' => 900, // 15 minutes
        'remember_me.lifetime' => 604800, // 1 week
        'csrf_token.news' => 'news',
        'csrf_token.category' => 'category',
        'csrf_token.comment' => 'comment'
    ];

    public function __construct(
        private readonly ParameterBagInterface $params
    ) {
    }

    private function getParam(string $key): mixed
    {
        try {
            return $this->params->get('app.security.' . $key);
        } catch (\Exception $e) {
            return self::DEFAULTS[$key] ?? null;
        }
    }

    public function getPasswordMinLength(): int
    {
        return (int) $this->getParam('password.min_length');
    }

    public function getPasswordMaxLength(): int
    {
        return (int) $this->getParam('password.max_length');
    }

    public function getLoginMaxAttempts(): int
    {
        return (int) $this->getParam('login.max_attempts');
    }

    public function getLoginLockoutTime(): int
    {
        return (int) $this->getParam('login.lockout_time');
    }

    public function getRememberMeLifetime(): int
    {
        return (int) $this->getParam('remember_me.lifetime');
    }

    public function getNewsTokenId(): string
    {
        return (string) $this->getParam('csrf_token.news');
    }

    public function getCategoryTokenId(): string
    {
        return (string) $this->getParam('csrf_token.category');
    }

    public function getCommentTokenId(): string
    {
        return (string) $this->getParam('csrf_token.comment');
    }
} 