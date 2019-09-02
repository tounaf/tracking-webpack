<?php

namespace App\Form;

use App\Entity\DecisionCloture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionClotureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libele', null, array(
                'label' => 'libelé'
            ))
            ->add('enable', null, array(
                'label' => 'Actif'
            ))
        ;
        if ($options['remove_field']) {
            $builder
                ->remove('libele', null, array(
                    'label' => 'libelé'
                ))
                ->remove('enable', null, array(
                    'label' => 'Actif'
                ))
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DecisionCloture::class,
            'remove_field' => false
        ]);
    }
}
