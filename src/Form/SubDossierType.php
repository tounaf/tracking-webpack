<?php

namespace App\Form;

use App\Entity\SubDossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class SubDossierType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', null, array(
                'label' => $this->trans->trans('Libellé')
            ))
            ->add('numeroSubDossier', null, array(
                'label' => $this->trans->trans('Numéro dossier')
            ))
//            ->add('dossier')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubDossier::class,
        ]);
    }
}
