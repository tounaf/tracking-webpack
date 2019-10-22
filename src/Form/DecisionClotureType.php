<?php

namespace App\Form;

use App\Entity\DecisionCloture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class DecisionClotureType extends AbstractType
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
                'label' => $this->trans->trans('Décision de justice *'),
                'required' => true
            ))
            ->add('enable', null, array(
                'label' => $this->trans->trans('label.enable')
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
