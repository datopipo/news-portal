<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\News;
use App\Constants\AppConstants;
use App\Form\Trait\FormFieldTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class NewsType extends AbstractType
{
    use FormFieldTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addTextField($builder, 'title', [
            'label' => 'Title',
            'attr' => [
                'placeholder' => 'Enter news title',
                'minlength' => AppConstants::getNewsTitleMinLength(),
                'maxlength' => AppConstants::getNewsTitleMaxLength()
            ]
        ]);

        $this->addTextareaField($builder, 'shortDescription', [
            'label' => 'Short Description',
            'attr' => [
                'placeholder' => 'Enter short description (max 500 characters)',
                'minlength' => AppConstants::getNewsShortDescMinLength(),
                'maxlength' => AppConstants::getNewsShortDescMaxLength()
            ]
        ]);

        $this->addTextareaField($builder, 'content', [
            'label' => 'Content',
            'attr' => [
                'rows' => 10,
                'placeholder' => 'Enter full content',
                'minlength' => AppConstants::getNewsContentMinLength()
            ]
        ]);

        $builder->add('pictureFile', FileType::class, [
            'label' => 'News Image',
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => AppConstants::getMaxFileSize(),
                    'mimeTypes' => AppConstants::getAllowedImageTypes(),
                    'mimeTypesMessage' => AppConstants::getMessages()['news']['image_type'],
                    'maxSizeMessage' => AppConstants::getMessages()['news']['image_size']
                ])
            ],
            'attr' => [
                'class' => 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
            ]
        ]);

        $builder->add('categories', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'title',
            'multiple' => true,
            'expanded' => true,
            'label' => 'Categories',
            'attr' => [
                'class' => 'form-check'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => AppConstants::getCsrfTokenIdNews()
        ]);
    }
}
