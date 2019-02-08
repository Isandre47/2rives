<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Emission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                    'class' => 'form-control',
                    'placeholder' => "Titre"
                ],
                'label' => 'Titre'
            ])
            ->add('resume', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Résumé"
                ],
                'label' => 'Résumé'
            ])
//            ->add('medias')
//            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
//                $medias = $event->getData()->getMedias();
//                $form = $event->getForm();
//                $form->add('medias', FileType::class, array(
//                    'data_class' => null,
//                    'attr' => array(
//                        'class'=> ""
//                    ),
//                    'label' => 'Vous souhaitez changer la photo?',
//                    'required' => false,
//                    'constraints' => empty($medias) ?
//                        [new Assert\NotBlank()] :
//                        []
//                ));
//
//            })
//            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event){
//                $medias = $event->getData()->getMedias();
//                $form = $event->getForm();
//                $form->add('medias', FileType::class, array(
//                    'data_class' => null,
//                    'attr' => array(
//                        'class' => ""
//                    ),
//                    'label' => 'Vous souhaitez changer la vidéo ?',
//                    'required' => false,
//                    'constraints' => empty($medias) ?
//                        [new Assert\NotBlank()] :
//                        []
//                ));
//            })
            ->add('lien', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Lien"
                ],
                'label' => 'Lien'
            ])
            ->add('category', EntityType::class, [
                'class'=> Category::class,
                'choice_label'=> 'type',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "prout"
                ],
                'label' => 'prout'
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
