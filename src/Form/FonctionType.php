<?php

namespace App\Form;

use App\Entity\Fonction;
use App\Entity\Profil;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('profil',EntityType::class, array(
                'class' => Profil::class,
                'placeholder' => 'Veuillez choisir un profil',
                'required' => true,
                'choice_label' => 'libele'
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
