<?php

namespace App\Form;

use App\Entity\FosUser;
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
            ->add('name',null, array(
                'required' => true,
                'label' => $this->trans->trans('Nom')
            ))
            ->add('lastname',null, array(
                'required' => true,
                'label' => $this->trans->trans('Prénoms')
            ))
            ->add('email', EmailType::class, array(
                'label' => $this->trans->trans('label.email'),
                'required' => true
            ))
            ->add('phoneNumber', null, array(
                'required' => true,
                'label' => $this->trans->trans('Téléphone')
            ))
            ->add('enabled')
        ;
        if ($options['remove_field']) {
           $builder
               ->remove('name',null, array(
                'required' => true,
                'label' => $this->trans->trans('Nom')
            ))
                ->remove('lastname',null, array(
                    'required' => true,
                    'label' => $this->trans->trans('Prénoms')
                ))
                ->remove('email', EmailType::class, array(
                    'label' => $this->trans->trans('label.email'),
                    'required' => true
                ))
                ->remove('phoneNumber', null, array(
                    'required' => true,
                    'label' => $this->trans->trans('Téléphone')
                ))
                ->remove('enabled');
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
