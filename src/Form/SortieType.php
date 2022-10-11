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
            ->add('nom')
            ->add('date_debut') //Date de debut de la Sortie
            ->add('duree') //En heures
            ->add('date_cloture') //Doit etre anterieure à la date de début
            ->add('nb_inscriptions_max', null, [
                'label' =>'Capacité maximum: ',
                'attr' =>[
                    'id' => 'idCapacite'
                ]
            ])
            ->add('description_infos',null,[
                'label' =>'Description: ',
                'attr' =>[
                    'id' =>'idDescription'
                ]])
            //->add('url_photo')
            //->add('etats_no_etat')
            ->add('lieux_no_lieu',EntityType::class,[
                'choice_label'=> 'nom_lieu',
                'class'=> Lieu::class,
                'expanded' => true,
                'attr' =>[
                    'id' => 'idLieu'
                ]
            ])
//            ->add('organisateur', EntityType::class,[
//                'disabled' => true,
//                'id' => 'idOrganisateur'
//            ]) // Utilisateur Connecté
//            ->add('site_organisateur') //Site de l'utilisateur connecté
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
