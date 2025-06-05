<?php

declare(strict_types=1);

namespace App\Form\Trait;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

trait FormFieldTrait
{
    protected function addTextField(FormBuilderInterface $builder, string $name, array $options = []): void
    {
        $builder->add($name, TextType::class, array_merge([
            'attr' => [
                'class' => 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
            ]
        ], $options));
    }

    protected function addTextareaField(FormBuilderInterface $builder, string $name, array $options = []): void
    {
        $builder->add($name, TextareaType::class, array_merge([
            'attr' => [
                'class' => 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                'rows' => 3
            ]
        ], $options));
    }
} 