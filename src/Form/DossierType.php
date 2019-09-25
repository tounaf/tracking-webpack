<?php

namespace App\Form;

use App\Entity\CategorieLitige;
use App\Entity\Devise;
use App\Entity\Dossier;
use App\Entity\EtapeSuivante;
use App\Entity\InformationPj;
use App\Entity\Societe;
use App\Entity\Statut;
use App\Entity\StatutsPersMorale;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
//            societe
            ->add('userEnCharge',null, array(
                'label' => $this->trans->trans('label.personne.en.charge'),
                'required' => true
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
            ->add('statutPartiAdverse', ChoiceType::class, array(
                'label' => $this->trans->trans('label.statut'),
                'placeholder' => 'Veuillez selectionnner',
                'choices' => [
                    'Persone physique' => 'Personne physique',
                    'Personne morale' => 'Personne morale'
                ]
            ))
            ->add('formePartieAdverse', EntityType::class, array(
                'label' => $this->trans->trans('label.form'),
                'class' => StatutsPersMorale::class,
                'choice_label' => 'libelle'
            ))
//            ->add('partieAdverse', PartiAdverseType::class, array(
//                'label' => 'partiadverse'
//            ))

            //resume fait
            ->add('resumeFait', TextareaType::class, array(
                'label' => $this->trans->trans('label.resume.fait')
            ))

            //litige
            ->add('categorie', EntityType::class, array(
                'class' => CategorieLitige::class,
                'choice_label' => 'libelle'
            ))

            ->add('dateLitige', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'required' => true,

                'label' => $this->trans->trans('label.date.litige'),
                'attr' => ['class' => 'js-datepicker','data-provide' => 'datepicker'],
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
            ->add('devise', EntityType::class, array(
                'class' => Devise::class,
                'label' => $this->trans->trans('label.devise'),
                'choice_label' => 'code'
            ))
            ->add('etapeSuivante', EntityType::class, array(
                'class' => EtapeSuivante::class,
                'choice_label' => 'libelle'
            ))
            ->add('echeance', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'required' => true,

                'label' => $this->trans->trans('label.echeance'),
                'attr' => ['class' => 'js-datepicker','data-provide' => 'datepicker'],

            ))
            ->add('situation', null, array(
                'label' => $this->trans->trans('label.situation')
            ))
            ->add('alerteDate', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'required' => true,

                'label' => $this->trans->trans('label.alert'),
                'attr' => ['class' => 'js-datepicker','data-provide' => 'datepicker'],
            ))

            ->add('subDossiers', CollectionType::class, array(
                'entry_type' => SubDossierType::class,
                'label' => '',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'attr' => array('class' => 'my-selector',)
                )
            )
//            ->add('numeroDossier')
//            ->add('libelle')
//            //PJ
//            //PJ Field to add file in pj
            ->add('File', FileType::class,[
                'label' => 'insérer pièces jointes',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                //"multiple" => true,
                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
            ])

            ->add('piecesJointes', EntityType::class, array(
                'class' => InformationPj::class,
                'choice_label' => 'libelle',
//                'data_class' =>
                'mapped' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dossier::class,
        ]);
    }
}
