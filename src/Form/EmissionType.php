<?php

namespace App\Form;

use App\Controller\DailyController;
use App\Entity\Category;
use App\Entity\Emission;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EmissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control m-4',
                    'placeholder' => "Titre"
                ],
                'label' => 'Titre'
            ])
            ->add('resume', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control m-4',
                    'placeholder' => "Résumé"
                ],
                'label' => 'Résumé'
            ])

            ->add('lien', EntityType::class, [
                'class' => Emission::class,
                'choice_label'=> 'title',
                'choice_value' => 'lien',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control m-4',
                    'placeholder' => "prout"
                ],
                'label' => 'Vidéo'
            ])
            ->add('category', EntityType::class, [
                'class'=> Category::class,
                'choice_label'=> 'type',
                'attr' => [
                    'class' => 'form-control m-4',
                    'placeholder' => "prout"
                ],
                'label' => 'Catégorie'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emission::class,
        ]);
    }
}
