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
        return self::$params->get('app.' . $key);
    }

    // Pagination
    public static function getDefaultPageSize(): int
    {
        return self::getParam('pagination.default_page_size');
    }

    public static function getMaxPageSize(): int
    {
        return self::getParam('pagination.max_page_size');
    }

    public static function getDefaultPage(): int
    {
        return self::getParam('pagination.default_page');
    }

    // News
    public static function getNewsTitleMinLength(): int
    {
        return self::getParam('news.title.min_length');
    }

    public static function getNewsTitleMaxLength(): int
    {
        return self::getParam('news.title.max_length');
    }

    public static function getNewsShortDescMinLength(): int
    {
        return self::getParam('news.short_desc.min_length');
    }

    public static function getNewsShortDescMaxLength(): int
    {
        return self::getParam('news.short_desc.max_length');
    }

    public static function getNewsContentMinLength(): int
    {
        return self::getParam('news.content.min_length');
    }

    public static function getNewsRecentLimit(): int
    {
        return self::getParam('news.recent_limit');
    }

    public static function getNewsTopLimit(): int
    {
        return self::getParam('news.top_limit');
    }

    // Category
    public static function getCategoryTitleMinLength(): int
    {
        return self::getParam('category.title.min_length');
    }

    public static function getCategoryTitleMaxLength(): int
    {
        return self::getParam('category.title.max_length');
    }

    // Comment
    public static function getCommentAuthorMinLength(): int
    {
        return self::getParam('comment.author.min_length');
    }

    public static function getCommentAuthorMaxLength(): int
    {
        return self::getParam('comment.author.max_length');
    }

    public static function getCommentContentMinLength(): int
    {
        return self::getParam('comment.content.min_length');
    }

    public static function getCommentContentMaxLength(): int
    {
        return self::getParam('comment.content.max_length');
    }

    // File Upload
    public static function getMaxFileSize(): int
    {
        return self::getParam('file_upload.max_size');
    }

    public static function getAllowedImageTypes(): array
    {
        return self::getParam('file_upload.allowed_types');
    }

    public static function getUploadDirectory(): string
    {
        return self::getParam('file_upload.directory');
    }

    // Email
    public static function getEmailMaxLength(): int
    {
        return self::getParam('email.max_length');
    }

    public static function getWeeklyStatsSubject(): string
    {
        return self::getParam('email.weekly_stats.subject');
    }

    public static function getWeeklyStatsFromEmail(): string
    {
        return self::getParam('email.weekly_stats.from');
    }

    // Security
    public static function getCsrfTokenIdComment(): string
    {
        return self::getParam('security.csrf_token.comment');
    }

    public static function getCsrfTokenIdNews(): string
    {
        return self::getParam('security.csrf_token.news');
    }

    public static function getCsrfTokenIdCategory(): string
    {
        return self::getParam('security.csrf_token.category');
    }

    // Validation
    public static function getNamePattern(): string
    {
        return self::getParam('validation.name_pattern');
    }

    public static function getNoHtmlPattern(): string
    {
        return self::getParam('validation.no_html_pattern');
    }

    // Messages
    public static function getMessages(): array
    {
        return self::getParam('messages');
    }
} 