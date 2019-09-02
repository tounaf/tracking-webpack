<?php

namespace App\Form;

use App\Entity\Statut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libele', null, array(
                'label' => 'libelé'
            ))
            ->add('enable', null, array(
                'label' => 'Acitf'
            ))
        ;
        if ($options['remove_field']){
            $builder
                ->remove('libele')
                ->remove('enable')
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Statut::class,
            'remove_field' => false
        ]);
    }
}
