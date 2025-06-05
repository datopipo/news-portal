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
use App\Constants\AppConstants;

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
                        'min' => AppConstants::COMMENT_AUTHOR_MIN_LENGTH,
                        'max' => AppConstants::COMMENT_AUTHOR_MAX_LENGTH,
                        'minMessage' => 'Your name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your name cannot be longer than {{ limit }} characters'
                    ]),
                    new Assert\Regex([
                        'pattern' => AppConstants::NAME_PATTERN,
                        'message' => 'Your name can only contain letters, numbers, spaces and hyphens'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your name',
                    'minlength' => AppConstants::COMMENT_AUTHOR_MIN_LENGTH,
                    'maxlength' => AppConstants::COMMENT_AUTHOR_MAX_LENGTH
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
                        'max' => AppConstants::EMAIL_MAX_LENGTH,
                        'maxMessage' => 'Email cannot be longer than {{ limit }} characters'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your email',
                    'maxlength' => AppConstants::EMAIL_MAX_LENGTH
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Comment',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your comment'
                    ]),
                    new Assert\Length([
                        'min' => AppConstants::COMMENT_CONTENT_MIN_LENGTH,
                        'max' => AppConstants::COMMENT_CONTENT_MAX_LENGTH,
                        'minMessage' => 'Your comment must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your comment cannot be longer than {{ limit }} characters'
                    ]),
                    new Assert\Regex([
                        'pattern' => AppConstants::NO_HTML_PATTERN,
                        'message' => 'HTML tags are not allowed in comments'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Write your comment here...',
                    'minlength' => AppConstants::COMMENT_CONTENT_MIN_LENGTH,
                    'maxlength' => AppConstants::COMMENT_CONTENT_MAX_LENGTH
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id'   => AppConstants::CSRF_TOKEN_ID_COMMENT
        ]);
    }
}
 