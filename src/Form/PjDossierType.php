<?php

namespace App\Form;

use App\Entity\PjDossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PjDossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt')
            ->add('filename')
            ->add('dossier')
            ->add('informationPj')
            ->add('File', FileType::class,[
                'label' => 'insérer pièces jointes',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                /* "multiple" => true,*/
                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PjDossier::class,
        ]);
    }
}
