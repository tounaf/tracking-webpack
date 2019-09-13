<?php

namespace App\Form;

use App\Entity\Intervenant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntervenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('convenu')
            ->add('payer')
            ->add('restePayer')
            ->add('statutIntervenant')
            ->add('devise')
            ->add('user')
            ->add('prestation')
        ;
        if ($options['remove_field']) {
            $builder
                ->remove('convenu')
                ->remove('payer')
                ->remove('restePayer')
                ->remove('statutIntervenant')
                ->remove('devise')
                ->remove('prestation')
                ->remove('user');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervenant::class,
            'remove_field' => false
        ]);
    }
}
