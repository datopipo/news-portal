<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionManager
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * Initialize session with user data after login
     */
    public function initializeUserSession(string $userIdentifier): void
    {
        $this->session->set('user_identifier', $userIdentifier);
        $this->session->set('login_time', new \DateTime());
        $this->session->set('last_activity', new \DateTime());
        $this->session->set('session_started', true);
    }

    /**
     * Update user activity timestamp
     */
    public function updateActivity(): void
    {
        if ($this->isUserLoggedIn()) {
            $this->session->set('last_activity', new \DateTime());
        }
    }

    /**
     * Get session data for logged in user
     * @return array<string, mixed>
     */
    public function getSessionData(): array
    {
        if (!$this->isUserLoggedIn()) {
            return [];
        }

        return [
            'user_identifier' => $this->session->get('user_identifier'),
            'login_time' => $this->session->get('login_time'),
            'last_activity' => $this->session->get('last_activity'),
            'session_id' => $this->session->getId(),
            'is_active' => $this->isSessionActive(),
        ];
    }

    /**
     * Check if user is logged in
     */
    public function isUserLoggedIn(): bool
    {
        return $this->tokenStorage->getToken() !== null 
            && $this->session->has('session_started');
    }

    /**
     * Check if session is still active (not expired)
     */
    public function isSessionActive(): bool
    {
        if (!$this->session->has('last_activity')) {
            return false;
        }

        $lastActivity = $this->session->get('last_activity');
        if (!$lastActivity instanceof \DateTime) {
            return false;
        }

        // Check if session has been inactive for more than 1 hour
        $now = new \DateTime();
        $diff = $now->getTimestamp() - $lastActivity->getTimestamp();
        
        return $diff < 3600; // 1 hour
    }

    /**
     * Manually destroy session (useful for programmatic logout)
     */
    public function destroySession(): void
    {
        $this->session->invalidate();
        $this->session->clear();
    }

    /**
     * Get session statistics
     * @return array<string, mixed>
     */
    public function getSessionStats(): array
    {
        if (!$this->isUserLoggedIn()) {
            return ['status' => 'not_logged_in'];
        }

        $loginTime = $this->session->get('login_time');
        $lastActivity = $this->session->get('last_activity');
        $now = new \DateTime();

        $sessionDuration = $loginTime instanceof \DateTime 
            ? $now->getTimestamp() - $loginTime->getTimestamp()
            : 0;

        $inactiveTime = $lastActivity instanceof \DateTime
            ? $now->getTimestamp() - $lastActivity->getTimestamp()
            : 0;

        return [
            'status' => 'logged_in',
            'session_duration_minutes' => round($sessionDuration / 60, 1),
            'inactive_time_minutes' => round($inactiveTime / 60, 1),
            'session_id' => $this->session->getId(),
            'user_identifier' => $this->session->get('user_identifier'),
        ];
    }
} 