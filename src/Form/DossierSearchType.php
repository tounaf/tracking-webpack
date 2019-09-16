<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class DossierSearchType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null,array(
                'label' => $this->trans->trans('NOM')
            ))
            ->add('reference', null, array(
                'label' => $this->trans->trans('RÉFÉRENCE')
            ))
            ->add('categorie', null, array(
                'label' => $this->trans->trans('CATÉGORIE')
            ))
            ->add('entite', null, array(
                'label' => $this->trans->trans('ENTITÉ')
            ))
            ->add('statut', null, array(
                'label' => $this->trans->trans('STATUT')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
