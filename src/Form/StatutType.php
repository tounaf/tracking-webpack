<?php

namespace App\Form;

use App\Entity\Statut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class StatutType extends AbstractType
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
                'label' => $this->trans->trans('label'),
                'required' => true
            ))
            ->add('enable', null, array(
                'label' => $this->trans->trans('label.enable')
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
