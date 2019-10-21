<?php

namespace App\Form;

use App\Entity\CategorieLitige;
use App\Entity\Devise;
use App\Entity\Dossier;
use App\Entity\EtapeSuivante;
use App\Entity\InformationPj;
use App\Entity\PjDossier;
use App\Entity\Societe;
use App\Entity\Statut;
use App\Entity\StatutsPersMorale;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DossierType extends AbstractType
{
    private $trans;
    private $auth;
    private $user;
    public function __construct(TranslatorInterface $translator, AuthorizationCheckerInterface $checker, Security $security)
    {
        $this->trans = $translator;
        $this->auth = $checker;
       $userCharge = $this->user = $security->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            societe
            /*->add('userEnCharge',null, array(
                'label' => $this->trans->trans('label.personne.en.charge'),
                'required' => true
            ))*/

            ->add('userEnCharge', EntityType::class, array(
                'class' => 'App\Entity\FosUser',
                'group_by' =>'societe.libele',
                'label' => $this->trans->trans('label.personne.en.charge'),
                'required' => true,
                //'placeholder' => $this->user->getName(),
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getUserCharge($this->user);
                }))

            ->add('raisonSocial', EntityType::class, array(
                'class' => 'App\Entity\Societe',
                'label' => $this->trans->trans('label.raison.social'),
                'required' => true,
                //'placeholder' => $this->user->getName(),
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getSocietecharge($this->user);
                }))
            //partie adverse
            ->add('nomPartieAdverse', null, array(
                'label' => $this->trans->trans('label.nom'),

            ))
            ->add('statutPartiAdverse', ChoiceType::class, array(
                'label' => $this->trans->trans('label.statut'),
                'placeholder' =>  $this->trans->trans('label.veuillezS'),
                'choices' => [
                    'Persone physique' => 'Personne physique',
                    'Personne morale' => 'Personne morale'
                ]
            ))
            ->add('formePartieAdverse', EntityType::class, array(
                'label' => $this->trans->trans('label.form'),
                'class' => StatutsPersMorale::class,
                'required' => false,
                'placeholder' =>  $this->trans->trans('label.veuillezS'),
                'choice_label' => 'libelle'
            ))
//            ->add('partieAdverse', PartiAdverseType::class, array(
//                'label' => 'partiadverse'
//            ))

            //resume fait
            ->add('resumeFait', TextareaType::class, array(
                'label' => $this->trans->trans('label.resume.fait'),
                'required' => false,
            ))

            //litige
            ->add('categorie', null, [
                'label'=> 'CATEGORIE',
                'placeholder' =>  $this->trans->trans('label.veuillezS'),
               'required'=>true,
               // 'choice_label' => 'libelle'
            ])
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
            ->add('statut', null, [
                'placeholder' =>  $this->trans->trans('label.veuillezS'),
                'required'=>true,
            ])
            ->add('sensLitige', ChoiceType::class, array(
                'label' => $this->trans->trans('label.sens.litige'),
                'placeholder' =>  $this->trans->trans('label.veuillezS'),
                'required'=> false,
                'choices' => [
                    'Positif' => 'Positif',
                    'Négatif' => 'Négatif'
                ]
            ))
            ->add('montant', null, array(
                'required'=>true,
                'label' => $this->trans->trans('label.montant')
            ))
            ->add('devise', EntityType::class, [
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();}
            ])
            ->add('etapeSuivante', EntityType::class, array(
                'placeholder' =>  $this->trans->trans('label.veuillezS'),
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
            ->add('situation', ChoiceType::class, array(
                'label' => $this->trans->trans('Instance'),
                'placeholder' =>  $this->trans->trans('label.veuillezS'),
                'choices' => [
                    'Devant le président du tribunal' => 'Devant le président du tribunal',
                    'En première instance' => 'En première instance',
                    'En appel' => 'En appel',
                    'En cassation' => 'En cassation',
                    'En arbitrage' => 'En arbitrage',
                ]
            ))
            ->add('alerteDate', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'required' => false,

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
        //   //PJ Field to add file in pj
          ->add('File', FileType::class,[
                'label' => 'insérer pièces jointes',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
            ])

            ->add('piecesJointes', EntityType::class, array(
                'class' => InformationPj::class,
                'choice_label' => 'libelle',
                'required' => false,
                'placeholder' =>'Veuillez selectionner',

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
