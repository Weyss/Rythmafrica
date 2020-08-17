<?php

namespace App\Form;


use App\Entity\Pays;
use App\Entity\Event;
use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
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
                    'accept'=>'.jpg, .jpeg, .png'
                ]
            ])
            ->add('startingAt', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'model_timezone' => 'Europe/Paris',
                'view_timezone' => 'Europe/Paris'
            ])
            ->add('closingAt',  DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'model_timezone' => 'Europe/Paris',
                'view_timezone' => 'Europe/Paris'
            ])
            ->add('adress')
            ->add('town')
            ->add('zipCode')
            ->add('entranceFee')
            ->add('artists', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'nickname',
                'multiple' => true,
                'placeholder' => 'Selectionner les artistes'
            ])
            ->add('pays', EntityType::class, [
                'class' => Pays::class,
                'choice_label' => 'nomFrFr',
                'placeholder' => 'Selectionner un pays'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
