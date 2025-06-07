<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\News;
use App\Form\Trait\CommonValidationTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    use CommonValidationTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'constraints' => $this->getTitleConstraints(),
            ])
            ->add('news', EntityType::class, [
                'class' => News::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => 'Related News',
                'help' => 'Select news items that belong to this category',
                'attr' => [
                    'class' => 'form-control',
                    'size' => 8,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'category_item'
        ]);
    }
}
