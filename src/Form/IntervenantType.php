<?php

namespace App\Form;

use App\Entity\Devise;
use App\Entity\FosUser;
use App\Entity\InformationPj;
use App\Entity\Intervenant;
use App\Entity\TypePrestation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                'attr' => array('maxlength' => 15,),
            ))
            ->add('payer',null, array(
                'label' => $this->trans->trans('label.payer'),
                'required'=>true,
                'attr' => array('maxlength' => 15,),

            ))
            ->add('restePayer',null, array(
                'label' => $this->trans->trans('label.restePayer'),
                'required'=>true,
                'attr' => array('maxlength' => 15,),

            ))
            ->add('statutIntervenant')
            ->add('deviseConvInt', EntityType::class, array(
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();
                }))
            ->add('devisePayerInt', EntityType::class, array(
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();
                }))
            ->add('deviseResteInt', EntityType::class, array(
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();
                }))
            ->add('prestation',EntityType::class, array(
                'label' => $this->trans->trans('label.typePrestation'),
                'class' => TypePrestation::class,
                'choice_label' => 'libelle'))
            ->add('nomPrenom',null,[
                'label' => 'NOM & PRENOM :',
                ])
            ->add('adresse')
            ->add('telephone', null, array(
                'required' => true,
                'attr' => array('maxlength' => 10, 'minlength' => 7),
                'label' => $this->trans->trans('label.tel')
            ))
            ->add('email', EmailType::class, array(
                'label' => $this->trans->trans('label.email'),
                'required' => true
            ))
            ->add('prefixPhone', null, array(
                'required' => true,
                'attr' => ['maxlength' => 4]
            ))
            ->add('piecesJointes', EntityType::class, array(
                'class' => InformationPj::class,
                'choice_label' => 'libelle',
//                'data_class' =>
                'mapped' => false
            ))
            ->add('File', FileType::class,[
                'label' => 'insérer pièces jointes',
                /*'attr' => ['class' => 'file'],*/
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
            ])

        ;
        if ($options['remove_field']) {
            $builder
                ->remove('convenu')
                ->remove('payer')
                ->remove('restePayer')
                ->remove('statutIntervenant')
                ->remove('deviseConvInt')
                ->remove('devisePayerInt')
                ->remove('deviseResteInt')
                ->remove('prestation')
                ->remove('nomPrenom')
                ->remove('adresse')
                ->remove('email')
                ->remove('telephone')
                ->remove('piecesJointes')
                ->remove('File')
                ->remove('prefixPhone');
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
