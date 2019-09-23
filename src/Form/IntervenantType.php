<?php

namespace App\Form;

use App\Entity\Devise;
use App\Entity\FosUser;
use App\Entity\Intervenant;
use App\Entity\TypePrestation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                'label'=> $this->trans->trans('label.devise'),
                'class' => Devise::class,
            ])
            ->add('devisePayer', EntityType::class, [
                'label'=> $this->trans->trans('label.devise'),
                'class' => Devise::class,
            ])
            ->add('deviseReste', EntityType::class, [
                'label'=> $this->trans->trans('label.devise'),
                'class' => Devise::class,
            ])
            ->add('prestation',EntityType::class, array(
                'label' => $this->trans->trans('label.titre.typePrestation'),
                'class' => TypePrestation::class,
                'choice_label' => 'libelle'))
            ->add('nomPrenom')
            ->add('adresse')
            ->add('telephone', null, array(
                'required' => true,
                'attr' => array('maxlength' => 10, 'minlength' => 10),
                'label' => $this->trans->trans('label.tel')
            ))
            ->add('email', EmailType::class, array(
                'label' => $this->trans->trans('label.email'),
                'required' => true
            ))
        ;
        if ($options['remove_field']) {
            $builder
                ->remove('convenu')
                ->remove('payer')
                ->remove('restePayer')
                ->remove('statutIntervenant')
                ->remove('devise')
                ->remove('devisePayer')
                ->remove('deviseReste')
                ->remove('prestation')
                ->remove('nomPrenom')
                ->remove('adresse')
                ->remove('email')
                ->remove('telephone');
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
