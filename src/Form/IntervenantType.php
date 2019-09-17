<?php

namespace App\Form;

use App\Entity\Devise;
use App\Entity\FosUser;
use App\Entity\Intervenant;
use App\Entity\TypePrestation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class IntervenantType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('convenu',null, array(
                'label' => $this->trans->trans('label.convenu'),
                'required'=>true,
            ))
            ->add('payer',null, array(
                'label' => $this->trans->trans('label.payer'),
                'required'=>true

            ))
            ->add('restePayer',null, array(
                'label' => $this->trans->trans('label.restePayer'),
                'required'=>true

            ))
            ->add('statutIntervenant')
            ->add('devise', EntityType::class, [
                'label'=> $this->trans->trans('column.nameDevise'),
                'class' => Devise::class,
            ])
            ->add('user', EntityType::class, [
                'label'=> $this->trans->trans('column.nameUser'),
                'class' => FosUser::class,
            ])
            ->add('prestation',EntityType::class, array(
                'label' => $this->trans->trans('label.titre.typePrestation'),
                'class' => TypePrestation::class,
                'choice_label' => 'libelle'))
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
