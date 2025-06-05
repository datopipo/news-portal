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

        try {
            return self::$params->get('app.' . $key);
        } catch (\Exception $e) {
            // Fallback to default values if parameter is not set
            return self::getDefaultValue($key);
        }
    }

    private static function getDefaultValue(string $key): mixed
    {
        $defaults = [
            'pagination.default_page_size' => 10,
            'pagination.max_page_size' => 100,
            'pagination.default_page' => 1,
            'news.title.min_length' => 3,
            'news.title.max_length' => 255,
            'news.short_desc.min_length' => 10,
            'news.short_desc.max_length' => 500,
            'news.content.min_length' => 50,
            'news.recent_limit' => 3,
            'news.top_limit' => 10,
            'category.title.min_length' => 2,
            'category.title.max_length' => 255,
            'comment.author_name.min_length' => 2,
            'comment.author_name.max_length' => 100,
            'comment.content.min_length' => 10,
            'comment.content.max_length' => 1000,
            'file.max_size' => '2M',
            'file.allowed_types' => ['image/jpeg', 'image/png', 'image/gif'],
            'security.csrf_token.news' => 'news_token',
            'security.csrf_token.category' => 'category_token',
            'security.csrf_token.comment' => 'comment_token'
        ];

        return $defaults[$key] ?? null;
    }

    // Pagination
    public static function getDefaultPageSize(): int
    {
        return (int) self::getParam('pagination.default_page_size');
    }

    public static function getMaxPageSize(): int
    {
        return (int) self::getParam('pagination.max_page_size');
    }

    public static function getDefaultPage(): int
    {
        return (int) self::getParam('pagination.default_page');
    }

    // News
    public static function getNewsTitleMinLength(): int
    {
        return (int) self::getParam('news.title.min_length');
    }

    public static function getNewsTitleMaxLength(): int
    {
        return (int) self::getParam('news.title.max_length');
    }

    public static function getNewsShortDescMinLength(): int
    {
        return (int) self::getParam('news.short_desc.min_length');
    }

    public static function getNewsShortDescMaxLength(): int
    {
        return (int) self::getParam('news.short_desc.max_length');
    }

    public static function getNewsContentMinLength(): int
    {
        return (int) self::getParam('news.content.min_length');
    }

    public static function getNewsRecentLimit(): int
    {
        return (int) self::getParam('news.recent_limit');
    }

    public static function getNewsTopLimit(): int
    {
        return (int) self::getParam('news.top_limit');
    }

    // Category
    public static function getCategoryTitleMinLength(): int
    {
        return (int) self::getParam('category.title.min_length');
    }

    public static function getCategoryTitleMaxLength(): int
    {
        return (int) self::getParam('category.title.max_length');
    }

    // Comment
    public static function getCommentAuthorNameMinLength(): int
    {
        return (int) self::getParam('comment.author_name.min_length');
    }

    public static function getCommentAuthorNameMaxLength(): int
    {
        return (int) self::getParam('comment.author_name.max_length');
    }

    public static function getCommentContentMinLength(): int
    {
        return (int) self::getParam('comment.content.min_length');
    }

    public static function getCommentContentMaxLength(): int
    {
        return (int) self::getParam('comment.content.max_length');
    }

    // File
    public static function getMaxFileSize(): string
    {
        return (string) self::getParam('file.max_size');
    }

    public static function getAllowedImageTypes(): array
    {
        return (array) self::getParam('file.allowed_types');
    }

    // Security
    public static function getCsrfTokenIdNews(): string
    {
        return (string) self::getParam('security.csrf_token.news');
    }

    public static function getCsrfTokenIdCategory(): string
    {
        return (string) self::getParam('security.csrf_token.category');
    }

    public static function getCsrfTokenIdComment(): string
    {
        return (string) self::getParam('security.csrf_token.comment');
    }

    // Messages
    public static function getMessages(): array
    {
        return [
            'news' => [
                'title_required' => 'News title cannot be blank',
                'title_length' => 'News title must be between {{ min }} and {{ max }} characters long',
                'short_desc_required' => 'Short description cannot be blank',
                'short_desc_length' => 'Short description must be between {{ min }} and {{ max }} characters long',
                'content_required' => 'Content cannot be blank',
                'content_min_length' => 'Content must be at least {{ limit }} characters long',
                'image_type' => 'Please upload a valid image file (JPEG, PNG, or GIF)',
                'image_size' => 'Image size must be less than {{ limit }}',
                'category_required' => 'Please select at least one category'
            ],
            'category' => [
                'title_required' => 'Category title cannot be blank',
                'title_length' => 'Category title must be between {{ min }} and {{ max }} characters long'
            ],
            'comment' => [
                'author_name_required' => 'Your name cannot be blank',
                'author_name_length' => 'Your name must be between {{ min }} and {{ max }} characters long',
                'email_required' => 'Email address cannot be blank',
                'email_invalid' => 'Please enter a valid email address',
                'content_required' => 'Comment cannot be blank',
                'content_length' => 'Comment must be between {{ min }} and {{ max }} characters long'
            ]
        ];
    }
} 