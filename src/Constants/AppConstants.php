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
            'category.title.min_length' => 2,
            'category.title.max_length' => 255,
            'comment.author_name.min_length' => 2,
            'comment.author_name.max_length' => 100,
            'comment.content.min_length' => 10,
            'comment.content.max_length' => 1000,
            'comment.email.max_length' => 180,
            'file.max_size' => '2M',
            'file.allowed_types' => ['image/jpeg', 'image/png', 'image/gif'],
            'security.csrf_token.news' => 'news_token',
            'security.csrf_token.category' => 'category_token',
            'security.csrf_token.comment' => 'comment_token'
        ];

        return $defaults[$key] ?? null;
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
