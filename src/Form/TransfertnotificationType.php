<?php

namespace App\Form;

use App\Entity\FosUser;
use App\Entity\Transfertnotification;
use Doctrine\ORM\EntityRepository;

use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransfertnotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('usernotif',EntityType::class, array(
                'label'=>'Notification actuelle',
                'class' => FosUser::class,
                'choice_label' => 'name',
                'required' => true,
                ))
            ->add('usertransfer', EntityType::class, array(
                'label'=>'Transférer à',
                'class' => FosUser::class,
                'choice_label' => 'name',
                'required' => true,
                ))
            ->add('datedebut', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'required' => true,

                'label' => 'date début',
                'attr' => ['class' => 'js-datepicker','data-provide' => 'datepicker'],
            ))
            ->add('datefin', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'model_timezone' => 'UTC',
                'view_timezone' => 'UTC',
                'required' => true,

                'label' => 'date fin',
                'attr' => ['class' => 'js-datepicker','data-provide' => 'datepicker'],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transfertnotification::class,
        ]);
    }
}
