<?php

namespace App\Form;

use App\Entity\Csv;
use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', FileType::class, [
            'mapped' => true
//            ,
//            'label' => 'Fichier Csv: ',
//            'required' => true,
//            'allow_delete' => true,
//            'download_label' => 'CsvParticipants',
//            'asset_helper' => true,
//            'download_uri' => true,
            ]
);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Csv::class,
        ]);
    }
}