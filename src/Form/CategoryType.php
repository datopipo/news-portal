<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\News;
use App\Constants\AppConstants;
use App\Form\Trait\FormFieldTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    use FormFieldTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addTextField($builder, 'title', [
            'label' => 'Category Title',
            'attr' => [
                'placeholder' => 'Enter category title',
                'minlength' => AppConstants::getCategoryTitleMinLength(),
                'maxlength' => AppConstants::getCategoryTitleMaxLength()
            ]
        ]);

        $builder->add('news', EntityType::class, [
            'class' => News::class,
            'choice_label' => 'title',
            'multiple' => true,
            'expanded' => false,
            'required' => false,
            'label' => 'Related News',
            'attr' => [
                'class' => 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                'size' => '5'
            ],
            'help' => 'Hold Ctrl/Cmd to select multiple news articles'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => AppConstants::getCsrfTokenIdCategory()
        ]);
    }
}
