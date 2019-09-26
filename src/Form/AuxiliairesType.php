<?php

namespace App\Form;

use App\Entity\Auxiliaires;
use App\Entity\Devise;
use App\Entity\FosUser;
use App\Entity\TypePrestation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
            ->add('fonction')
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
            ->add('devise', EntityType::class, array(
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
            ->add('deviseReste',EntityType::class, array(
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();
                }))
            ->add('prestation',EntityType::class, array(
                'label' => $this->trans->trans('label.titre.typePrestation'),
                'class' => TypePrestation::class,
                'choice_label' => 'libelle'))
            ->add('prefixPhone', null, array(
                'required' => true,
                'attr' => ['maxlength' => 4]
            ))
        ;
        if ($options['remove_field']) {
            $builder
                ->remove('convenu')
                ->remove('payer')
                ->remove('restePayer')
                ->remove('statutIntervenant')
                ->remove('devise')
                ->remove('deviseAuxiPayer')
                ->remove('deviseReste')
                ->remove('prestation')
                ->remove('fonction')
                ->remove('nomPrenom')
                ->remove('adresse')
                ->remove('email')
                ->remove('telephone')
                ->remove('prefixPhone');
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
