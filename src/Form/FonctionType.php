<?php

namespace App\Form;

use App\Entity\Fonction;
use App\Entity\Profil;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class FonctionType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libele',null, array(
                'label' => $this->trans->trans('label')
            ))
            ->add('profil',EntityType::class, array(
                'class' => Profil::class,
                'placeholder' => $this->trans->trans('label.choose.profil'),
                'required' => true,
                'choice_label' => 'libele'
            ))
            ->add('enable', null, array(
                'label' => $this->trans->trans('label.enable')
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
