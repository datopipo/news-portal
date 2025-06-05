<?php

namespace App\Constants;

class AppConstants
{
    // Pagination
    public const DEFAULT_PAGE_SIZE = 10;
    public const MAX_PAGE_SIZE = 50;
    public const DEFAULT_PAGE = 1;

    // News
    public const NEWS_TITLE_MIN_LENGTH = 3;
    public const NEWS_TITLE_MAX_LENGTH = 255;
    public const NEWS_SHORT_DESC_MIN_LENGTH = 10;
    public const NEWS_SHORT_DESC_MAX_LENGTH = 500;
    public const NEWS_CONTENT_MIN_LENGTH = 50;
    public const NEWS_RECENT_LIMIT = 3;
    public const NEWS_TOP_LIMIT = 10;

    // Category
    public const CATEGORY_TITLE_MIN_LENGTH = 2;
    public const CATEGORY_TITLE_MAX_LENGTH = 255;

    // Comment
    public const COMMENT_AUTHOR_MIN_LENGTH = 2;
    public const COMMENT_AUTHOR_MAX_LENGTH = 255;
    public const COMMENT_CONTENT_MIN_LENGTH = 10;
    public const COMMENT_CONTENT_MAX_LENGTH = 1000;

    // File Upload
    public const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    public const ALLOWED_IMAGE_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif'
    ];
    public const UPLOAD_DIRECTORY = 'uploads/pictures';

    // Email
    public const EMAIL_MAX_LENGTH = 255;
    public const WEEKLY_STATS_SUBJECT = 'Weekly News Statistics - Top 10 Articles';
    public const WEEKLY_STATS_FROM_EMAIL = 'noreply@newsportal.com';

    // Security
    public const CSRF_TOKEN_ID_COMMENT = 'comment_item';
    public const CSRF_TOKEN_ID_NEWS = 'news_item';
    public const CSRF_TOKEN_ID_CATEGORY = 'category_item';

    // Validation
    public const NAME_PATTERN = '/^[a-zA-Z0-9\s\-]+$/';
    public const NO_HTML_PATTERN = '/^[^<>]*$/';

    // Messages
    public const MESSAGES = [
        'news' => [
            'title_required' => 'Please enter a title',
            'title_length' => 'Title must be between {{ limit }} and {{ limit }} characters',
            'short_desc_required' => 'Please enter a short description',
            'short_desc_length' => 'Short description must be between {{ limit }} and {{ limit }} characters',
            'content_required' => 'Please enter the content',
            'content_min_length' => 'Content must be at least {{ limit }} characters long',
            'image_type' => 'Please upload a valid image file (JPEG, PNG, GIF)',
            'image_size' => 'Image size must be less than 5MB',
            'category_required' => 'Please select at least one category'
        ],
        'category' => [
            'title_required' => 'Please enter a category title',
            'title_length' => 'Category title must be between {{ limit }} and {{ limit }} characters'
        ],
        'comment' => [
            'author_required' => 'Please enter your name',
            'author_length' => 'Name must be between {{ limit }} and {{ limit }} characters',
            'author_pattern' => 'Name can only contain letters, numbers, spaces and hyphens',
            'email_invalid' => 'Please enter a valid email address',
            'email_length' => 'Email cannot be longer than {{ limit }} characters',
            'content_required' => 'Please enter your comment',
            'content_length' => 'Comment must be between {{ limit }} and {{ limit }} characters',
            'content_pattern' => 'HTML tags are not allowed in comments'
        ]
    ];
} 