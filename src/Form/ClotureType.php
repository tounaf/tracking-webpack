<?php

namespace App\Form;

use App\Entity\Cloture;
use App\Entity\Devise;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class ClotureType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateCloture',DateType::class,[
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'model_timezone' => 'UTC',
                    'view_timezone' => 'UTC',
                    'required' => false,
                    'label' => 'DATE DE CLÔTURE',
                    'attr' => ['class' => 'js-datepicker','data-provide' => 'datepicker','readonly'=>''],
            ])
            ->add('juridiction',TextType::class,[
                'label'=> 'JURIDICTION',
                'required' =>false

            ])
            ->add('sensDecision', ChoiceType::class,[
                'label'=> 'SENS DE LA DECISION DE JUSTICE',
                'placeholder'=>'Sélectionner',
                'required'=>false,
                'choices'=>[
                    'Favorable'=>'Favorable',
                    'Défavorable'=>'Défavorable',
                ]
            ])
            ->add('risque', ChoiceType::class,[
                'label'=> 'RISQUE',
                'placeholder'=>'Sélectionner',
                'required'=>false,
                'choices'=>[
                    'Faible'=>'Faible',
                    'Moyen'=>'Moyen',
                    'Elevé'=>'Elevé',
                    'Critique'=>'Critique',

                ]
            ])
            ->add('typeCloture', ChoiceType::class,[
                'label'=> 'TYPES DE CLÔTURE',
                'placeholder'=>'Sélectionner',
                'required'=>false,
                'choices'=>[
                    'Amiable'=>'Amiable',
                    'Justice'=>'Justice',
                ]
            ])
            ->add('gainCondamnation',ChoiceType::class, [
                'label' =>false,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'Condamnation' => 'Condamnation',
                    'Gain' => 'Gain',

                ],
                'required' => true,

            ])
            ->add('montantGainCondamn',null,[
                'label'=>'MONTANT',
                'required'=>false
            ])
            ->add('montantInitial',null,[
                'label'=>'MONTANT INITIAL',
                'required'=>false
            ])
            ->add('montantIntervenant',null,[
                'label'=>'MONTANT HONORAIRES AVOCAT',
                'required'=>false
            ])
            ->add('montantAuxiliaires',null,[
                'label'=>'MONTANT HONORAIRES AUTRES AUXILIAIRES',
                'required'=>false
            ])
            ->add('decisionLitige',null,[
                'label'=> 'DECISION DU LITIGE',
                'placeholder'=>'Sélectionner',
                'required'=> false,
            ])
            ->add('niveauDecision',null,[
                'label'=> 'NIVEAU DE LA DECISION DE LITIGE',
                'placeholder'=>'Sélectionner',
                'required'=> false,])
            ->add('natureDecision',null,[
                'label'=> 'NATURE DE LA DECISION DE JUSTICE',
                'placeholder'=>'Sélectionner',
                'required'=> false,])
         /*   ->add('devise', EntityType::class, [
                'label'=> $this->trans->trans('label.devise'),
                'class' => Devise::class,
                'required' => false
            ])*/

            ->add('devise', EntityType::class, array(
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
               // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();
                }))
            ->add('deviseInitial', EntityType::class, [
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();}
            ])
            ->add('deviseAvocat', EntityType::class, [
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();}
            ])
            ->add('deviseAuxiliaires', EntityType::class, [
                'class' => 'App\Entity\Devise',
                'label' =>  $this->trans->trans('label.devise'),
                // 'choice_label' => 'ch',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getDevise();}
            ])
            ->add('dossier')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cloture::class,
        ]);
    }
}
