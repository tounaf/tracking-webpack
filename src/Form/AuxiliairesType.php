<?php

namespace App\Form;

use App\Entity\Auxiliaires;
use App\Entity\Devise;
use App\Entity\FosUser;
use App\Entity\InformationPj;
use App\Entity\TypePrestation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class AuxiliairesType extends AbstractType
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
                'required'=>false,
                'attr' => array('maxlength' => 15,'class'=>'money'),
            ))
            ->add('payer',null, array(
                'label' => $this->trans->trans('label.payer'),
                'required'=>false,
                'attr' => array('maxlength' => 15,'class'=>'money'),

            ))
            ->add('restePayer',null, array(
                'label' => $this->trans->trans('label.restePayer'),
                'required'=>false,
                'attr' => array('maxlength' => 15,'class'=>'money'),
            ))
            ->add('statutIntervenant')
            ->add('fonction')


            ->add('nomPrenom',null,[
                'label' => 'PRENOM & NOM',
            ])
            ->add('adresse')
            ->add('telephone', null, array(
                'required' => true,
                'attr' => array('maxlength' => 10, 'minlength' => 7),
                'label' => $this->trans->trans('TELEPHONE')
            ))
            ->add('email', EmailType::class, array(
                'label' => $this->trans->trans('EMAIL'),
                'required' => false
            ))
            ->add('deviseAuxiConv', EntityType::class, array(
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();
                }))
            ->add('deviseAuxiPayer', EntityType::class, array(
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();
                }))
            ->add('deviseAuxiReste',EntityType::class, array(
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();
                }))
            ->add('piecesJointesAux', EntityType::class, array(
                'placeholder' =>'Veuillez sélectionner',
                'required' => false ,
                'class' => InformationPj::class,
                'placeholder' =>  $this->trans->trans('label.veuillezS'),
                'required'=>false,
                'label' => $this->trans->trans('label.infoPj'),
//                'data_class' =>
                'mapped' => false
            ))
            ->add('FileAux', FileType::class,[
                'label' => 'insérer pièces jointes',
               /* 'attr' => ['class' => 'file'],*/
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
            ])

            ->add('prestation',EntityType::class, array(
                'placeholder' =>  $this->trans->trans('label.veuillezS'),
                'label' => $this->trans->trans('label.titre.typePrestation'),
                'class' => TypePrestation::class,
                'choice_label' => 'libelle'))
            ->add('prefixPhone', null, array(
                'required' => true,
                'attr' => ['maxlength' => 4, 'placeholder' =>  '+261']
            ))
        ;
        if ($options['remove_field']) {
            $builder
                ->remove('convenu')
                ->remove('payer')
                ->remove('restePayer')
                ->remove('statutIntervenant')
                ->remove('deviseAuxiConv')
                ->remove('deviseAuxiPayer')
                ->remove('deviseAuxiReste')
                ->remove('prestation')
                ->remove('fonction')
                ->remove('nomPrenom')
                ->remove('adresse')
                ->remove('email')
                ->remove('telephone')
                ->remove('prefixPhone')
                ->remove('piecesJointesAux')
                ->remove('deviseAuxiConv')
                ->remove('deviseAuxiReste')
                ->remove('FileAux');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Auxiliaires::class,
            'remove_field' => false,
        ]);
    }
}
