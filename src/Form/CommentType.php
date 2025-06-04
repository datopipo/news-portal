<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('authorName', TextType::class, [
                'label' => 'Your Name',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your name'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Your name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your name cannot be longer than {{ limit }} characters'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\s\-]+$/',
                        'message' => 'Your name can only contain letters, numbers, spaces and hyphens'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your name',
                    'minlength' => 2,
                    'maxlength' => 255
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email (optional)',
                'required' => false,
                'constraints' => [
                    new Assert\Email([
                        'message' => 'Please enter a valid email address'
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Email cannot be longer than {{ limit }} characters'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your email',
                    'maxlength' => 255
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Comment',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your comment'
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'Your comment must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your comment cannot be longer than {{ limit }} characters'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[^<>]*$/',
                        'message' => 'HTML tags are not allowed in comments'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Write your comment here...',
                    'minlength' => 10,
                    'maxlength' => 1000
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id'   => 'comment_item'
        ]);
    }
}
 