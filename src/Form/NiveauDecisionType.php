<?php

namespace App\Form;

use App\Entity\NiveauDecision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class NiveauDecisionType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => $this->trans->trans('Niveau de décision de justice :'),
                'required' => true,
                'attr' => ['requiredMessage' => 'Veuillez renseigner ce champs s\'il vous plait'],
            ])
            ->add('isActif', CheckboxType::class, [
                'required' => false,
                'label' => 'Actif',
            ]);
        if ($options['remove_field']) {
            $builder
                ->remove('libelle')
                ->remove('isActif');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NiveauDecision::class,
            'remove_field' => false
        ]);
    }
}
