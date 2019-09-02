<?php

namespace App\Form;

use App\Entity\Fonction;
use App\Entity\FosUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class FosUserType extends AbstractType
{
    private $trans;

    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'required' => true,
                'label' => $this->trans->trans('Nom')
            ))
            ->add('lastname', null, array(
                'required' => true,
                'label' => $this->trans->trans('Prénoms')
            ))
            ->add('email', EmailType::class, array(
                'label' => $this->trans->trans('label.email'),
                'required' => true
            ))
            ->add('phoneNumber', null, array(
                'required' => true,
                'attr' => array('maxlength' => 10, 'minlength' => 10),
                'label' => $this->trans->trans('Téléphone')
            ))
            ->add('enabled')
            ->add('societe', EntityType::class, array(
                'class' => 'App\Entity\Societe',
                'choice_label' => 'libele',
                'required' => true,
                'placeholder' => $this->trans->trans('label.choice.societe')
            ))
            ->add('fonction', EntityType::class, array(
                'class' => Fonction::class,
                'choice_label' => 'libele',
                'required' => true,
            ));
        if ($options['remove_field']) {
            $builder
                ->remove('name')
                ->remove('lastname')
                ->remove('email')
                ->remove('phoneNumber')
                ->remove('enabled')
                ->remove('societe')
                ->remove('fonction');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FosUser::class,
            'remove_field' => false
        ]);
    }
}
