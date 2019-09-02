<?php

namespace App\Form;

use App\Entity\EtapeSuivante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtapeSuivanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class,[
                'label'=> 'Libellé',
                'required' => true,
                'attr'=> ['requiredMessage' => 'Veuillez renseigner ce champs s\'il vous plait'],
            ])
            ->add('isActif', CheckboxType::class,[
                'required' => false,
                'label'=> 'Actif',
            ]);
        if ($options['remove_field']) {
            $builder
                ->remove('libelle')
                ->remove('isActif');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EtapeSuivante::class,
            'remove_field' => false,
        ]);
    }
}
