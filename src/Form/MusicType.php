<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Category;
use App\Entity\Music;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MusicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '8M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Veuillez mettre une image',
                    ])
                ],
                'attr' => [
                    'accept'=>'.jpg, .jpeg, .png, .gif'
                ]
            ])
            ->add('music', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '16M',
                        'mimeTypes' => [
                            'application/octet-stream',
                            'audio/mpeg'
                        ],
                        
                        'mimeTypesMessage' => 'Veuillez mettre une musique',
                    ])
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Selectionner une catégorie'
            ])
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'nickname',
                'placeholder' => 'Selectionner un artiste'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Music::class,
        ]);
    }
}
