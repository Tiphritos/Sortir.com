<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_lieu',null,[
                'attr'=>[
                    'id'=>'nom'
                ]
            ])
            ->add('rue',null,[
                'attr'=>[
                    'id'=>'rue'
                ]
            ])

            ->add('villes_no_ville',EntityType::class,[
                'label'=>'Ville :',
                'choice_label'=> 'nom_ville',
                'class' => Ville::class,
                'attr'=>[
                    'id'=>'ville'
                ]
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
