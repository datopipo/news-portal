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
            'title_required' => 'Title cannot be blank',
            'title_length' => 'Title must be between {{ min }} and {{ max }} characters',
            'short_desc_required' => 'Short description cannot be blank',
            'short_desc_length' => 'Short description must be between {{ min }} and {{ max }} characters',
            'content_required' => 'Content cannot be blank',
            'content_min_length' => 'Content must be at least {{ limit }} characters long',
            'category_required' => 'You must select at least one category',
            'image_size' => 'The image size should not exceed 5MB',
            'image_type' => 'Please upload a valid image file (JPEG, PNG, GIF)'
        ],
        'category' => [
            'title_required' => 'Category title cannot be blank',
            'title_length' => 'Category title must be between {{ min }} and {{ max }} characters',
            'title_pattern' => 'Category title can only contain letters, numbers, spaces and hyphens'
        ],
        'comment' => [
            'author_required' => 'Please enter your name',
            'author_length' => 'Your name must be between {{ min }} and {{ max }} characters',
            'author_pattern' => 'Your name can only contain letters, numbers, spaces and hyphens',
            'content_required' => 'Please enter your comment',
            'content_length' => 'Your comment must be between {{ min }} and {{ max }} characters',
            'content_pattern' => 'HTML tags are not allowed in comments',
            'email_invalid' => 'Please enter a valid email address',
            'email_length' => 'Email cannot be longer than {{ limit }} characters'
        ]
    ];
} 