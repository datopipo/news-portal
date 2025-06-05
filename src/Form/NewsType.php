<?php

declare(strict_types=1);

namespace App\Form;

use App\Constants\SecurityConstants;
use App\Entity\Category;
use App\Entity\News;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsType extends AbstractType
{
    public function __construct(
        private readonly SecurityConstants $securityConstants
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a title']),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Title should be at least {{ limit }} characters',
                        'max' => 255,
                        'maxMessage' => 'Title should not exceed {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('shortDescription', TextType::class, [
                'label' => 'Short Description',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a short description']),
                    new Length([
                        'min' => 20,
                        'minMessage' => 'Short description should be at least {{ limit }} characters',
                        'max' => 300,
                        'maxMessage' => 'Short description should not exceed {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter content']),
                    new Length([
                        'min' => 100,
                        'minMessage' => 'Content should be at least {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
                'attr' => [
                    'size' => 5,
                    'class' => 'form-select'
                ],
                'help' => 'Hold Ctrl/Cmd to select multiple categories'
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Published',
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(['maxSize' => '5M']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => $this->securityConstants->getNewsTokenId()
        ]);
    }
}
