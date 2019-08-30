<?php

namespace App\Form;

use App\Entity\Fonction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FonctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libele',null, array(
                'label' => 'Libellé'
            ))
            ->add('profil',ChoiceType::class, array(
                'choices' => [
                    'Super Administrateur' => 'Super Administrateur',
                    'Administrateur' => 'Administrateur',
                    'Juriste' => 'Juriste'
                ],
                'placeholder' => 'Veuillez choisir un profil',
                'required' => true,
                'label' => 'Profil'
            ))
            ->add('enable', null, array(
                'label' => 'Actif'
            ))
        ;
        if ($options['remove_field']) {
            $builder
                ->remove('libele')
                ->remove('profil')
                ->remove('enable');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fonction::class,
            'remove_field' => false
        ]);
    }
}
