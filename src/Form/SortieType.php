<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' =>'Nom de la sortie: ',
                'attr' =>[
                    'id' =>'idTitre'
                ]
            ])
            //Date de debut de la Sortie
            ->add('date_debut', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget'=>'single_text',
                'input_format'=>'d-m-Y H:i',
                'label'=>'Date de la sortie: ',
                'attr' =>[
                    'id' =>'idDebut',

                ]
            ])
            ->add('duree', null, [
                'label'=>'Durée en minutes: ',
                'attr' =>[
                    'id' =>'idDuree',
                    'step'=> 15,
                ]

            ]) //En heures
            //Doit etre anterieure à la date de début
            ->add('date_cloture', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget'=>'single_text',
                'input_format'=>'d-m-Y H:i',
                'label'=>'Date de clotûre: ',
                'attr' => ['steps' => '15']
            ])

            ->add('nb_inscriptions_max', null, [
                'label' =>'Nombre de places: ',
                'attr' =>[
                    'id' => 'idParticipantsMaximum'
                ]
            ])
            ->add('description_infos',null,[
                'label' =>'Détails: ',
                'attr' =>[
                    'id' =>'idDescription'
                ]])

            ->add('lieux_no_lieu',EntityType::class,[
                'label'=>'Lieu: ',
                'choice_label'=> 'nom_lieu',
                'class'=> Lieu::class,
                'placeholder'=>'Choisissez un lieu',
                'attr' =>[
                    'id' => 'idLieu'
                ]
            ])
            ->add('imageFile', FileType::class,[
                    'required'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
