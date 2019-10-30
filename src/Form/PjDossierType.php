<?php

namespace App\Form;

use App\Entity\InformationPj;
use App\Entity\PjDossier;
use Bnbc\UploadBundle\Form\Type\AjaxfileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\File;

class PjDossierType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('File', FileType::class,[
                'label' => $this->trans->trans('label.File'),
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                /*'class' => 'labelpj',*/

                /* "multiple" => true,*/
                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => true,
                'constraints' => array(
                    new File([
                        'maxSize' => '400M',

                    ])
                )]
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
            )
            ->add('infoPj',EntityType::class, array(
                'label' => $this->trans->trans('label.infoPj'),
                'placeholder' => $this->trans->trans('label.veuillezS'),
                'class' => InformationPj::class,
                'choice_label' => 'libelle'))

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PjDossier::class,
        ]);
    }
}
