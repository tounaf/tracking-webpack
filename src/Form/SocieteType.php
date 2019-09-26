<?php

namespace App\Form;

use App\Entity\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class SocieteType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libele', null, array(
                'label' => $this->trans->trans('label.societe'),
                'required' => true,
                'attr' => ['class' => 'input-entite-name']
            ))
            ->add('trigramme', null, array(
                'label' => $this->trans->trans('Acronyme :'),
                'required' => true,
                'attr' => array('maxlength' => 3,'class' => 'input-entite-name')
            ))
            ->add('enable', null, array(
                'label' => $this->trans->trans('label.enable')
            ))
        ;
        if ($options['remove_field']) {
            $builder
                ->remove('libele')
                ->remove('trigramme')
                ->remove('enable');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Societe::class,
            'remove_field' => false
        ]);
    }
}
