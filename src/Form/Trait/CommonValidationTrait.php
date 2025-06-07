<?php

declare(strict_types=1);

namespace App\Form\Trait;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraint;

trait CommonValidationTrait
{
    /**
     * Get common title validation constraints
     * @return Constraint[]
     */
    protected function getTitleConstraints(int $minLength = 3, int $maxLength = 100): array
    {
        return [
            new NotBlank(['message' => 'Please enter a title']),
            new Length([
                'min' => $minLength,
                'minMessage' => 'Title should be at least {{ limit }} characters',
                'max' => $maxLength,
                'maxMessage' => 'Title should not exceed {{ limit }} characters',
            ]),
        ];
    }

    /**
     * Get common content validation constraints
     * @return Constraint[]
     */
    protected function getContentConstraints(int $minLength = 10, int $maxLength = 5000): array
    {
        return [
            new NotBlank(['message' => 'Please enter content']),
            new Length([
                'min' => $minLength,
                'minMessage' => 'Content should be at least {{ limit }} characters',
                'max' => $maxLength,
                'maxMessage' => 'Content should not exceed {{ limit }} characters',
            ]),
        ];
    }

    /**
     * Get common description validation constraints
     * @return Constraint[]
     */
    protected function getDescriptionConstraints(int $minLength = 20, int $maxLength = 300): array
    {
        return [
            new NotBlank(['message' => 'Please enter a description']),
            new Length([
                'min' => $minLength,
                'minMessage' => 'Description should be at least {{ limit }} characters',
                'max' => $maxLength,
                'maxMessage' => 'Description should not exceed {{ limit }} characters',
            ]),
        ];
    }

    /**
     * Get common name validation constraints
     * @return Constraint[]
     */
    protected function getNameConstraints(int $minLength = 2, int $maxLength = 50): array
    {
        return [
            new NotBlank(['message' => 'Please enter your name']),
            new Length([
                'min' => $minLength,
                'minMessage' => 'Your name should be at least {{ limit }} characters',
                'max' => $maxLength,
                'maxMessage' => 'Your name should be at most {{ limit }} characters',
            ]),
        ];
    }

    /**
     * Get common email validation constraints
     * @return Constraint[]
     */
    protected function getEmailConstraints(int $maxLength = 180): array
    {
        return [
            new NotBlank(['message' => 'Please enter your email']),
            new Email(['message' => 'Please enter a valid email address']),
            new Length([
                'max' => $maxLength,
                'maxMessage' => 'Email should not exceed {{ limit }} characters',
            ]),
        ];
    }
} 