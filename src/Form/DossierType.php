<?php

namespace App\Form;

use App\Entity\CategorieLitige;
use App\Entity\Dossier;
use App\Entity\EtapeSuivante;
use App\Entity\Societe;
use App\Entity\Statut;
use App\Entity\StatutsPersMorale;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class DossierType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //societe
            ->add('userEnCharge',null, array(
                'label' => $this->trans->trans('label.personne.en.charge')
            ))
            ->add('raisonSocial', EntityType::class, array(
                'label' => $this->trans->trans('label.raison.social'),
                'class' => Societe::class,
                'choice_label' =>'libele'
            ))
            //partie adverse
            ->add('nomPartieAdverse', null, array(
                'label' => $this->trans->trans('label.nom'),

            ))
            ->add('statutPartiAdverse', null, array(
                'label' => $this->trans->trans('label.statut')
            ))
            ->add('formePartieAdverse', EntityType::class, array(
                'label' => $this->trans->trans('label.form'),
                'class' => StatutsPersMorale::class,
                'choice_label' => 'libelle'
            ))
            ->add('partieAdverse', CollectionType::class, array(
                'entry_type' => PartiAdverseType::class,
                'entry_options' => ['label' => false]
            ))

            //resume fait
            ->add('resumeFait', TextareaType::class, array(
                'label' => $this->trans->trans('label.resume.fait')
            ))

            //litige
            ->add('categorie', EntityType::class, array(
                'class' => CategorieLitige::class,
                'choice_label' => 'libelle'
            ))
            ->add('dateLitige', DateType::class, array(
                'label' => $this->trans->trans('label.date.litige'),
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ))
            ->add('nomDossier', null, array(
                'label' => $this->trans->trans('label.nom.dossier')
            ))
            ->add('statut', EntityType::class, array(
                'label' => $this->trans->trans('label.statut'),
                'class' => Statut::class,
                'choice_label' => 'libele'
            ))
            ->add('sensLitige', null, array(
                'label' => $this->trans->trans('label.sens.litige')
            ))
            ->add('montant', null, array(
                'label' => $this->trans->trans('label.montant')
            ))
            ->add('etapeSuivante', EntityType::class, array(
                'class' => EtapeSuivante::class,
                'choice_label' => 'libelle'
            ))
            ->add('echeance', DateType::class, array(
                'label' => $this->trans->trans('label.echeance'),
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker']

            ))
            ->add('situation', null, array(
                'label' => $this->trans->trans('label.situation')
            ))
            ->add('alerteDate', DateType::class, array(
                'label' => $this->trans->trans('label.alerte'),
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker']
            ))
            //
//            ->add('numeroDossier')
//            ->add('libelle')
//            //PJ
//            ->add('piecesJointes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dossier::class,
        ]);
    }
}
