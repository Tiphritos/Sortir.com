<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('date_debut', null, [
                'label'=>'Date et heure de la sortie: ',
                'attr' =>[
                    'id' =>'idDebut'
                ]
            ])
            ->add('duree', null, [
                'label'=>'Durée en minutes: ',
                'attr' =>[
                    'id' =>'idDuree'
                ]
            ]) //En heures
            //Doit etre anterieure à la date de début
            ->add('date_cloture', null, [
                'label'=>'Date limite d\'inscription: '
            ])

            ->add('nb_inscriptions_max', null, [
                'label' =>'Nombre de places: ',
                'attr' =>[
                    'id' => 'idParticipantsMaximum'
                ]
            ])
            ->add('description_infos',null,[
                'label' =>'Description et infos: ',
                'attr' =>[
                    'id' =>'idDescription'
                ]])
            //->add('url_photo')
            //->add('etats_no_etat')
            ->add('lieux_no_lieu',EntityType::class,[
                'label'=>'Lieu: ',
                'choice_label'=> 'nom_lieu',
                'class'=> Lieu::class,
                'placeholder'=>'Choisissez un lieu',
                'attr' =>[
                    'id' => 'idLieu'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
