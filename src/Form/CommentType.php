<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Comment;
use App\Form\Trait\CommonValidationTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    use CommonValidationTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('authorName', TextType::class, [
                'constraints' => $this->getNameConstraints(),
            ])
            ->add('email', EmailType::class, [
                'constraints' => $this->getEmailConstraints(),
            ])
            ->add('content', TextareaType::class, [
                'constraints' => $this->getContentConstraints(10, 1000),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'comment_item'
        ]);
    }
}
 