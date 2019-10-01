<?php

namespace App\Form;

use App\Entity\InformationPj;
use App\Entity\PjCloture;
use Bnbc\UploadBundle\Form\Type\AjaxfileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\File;

class PjClotureType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' 	=> 'INSÉRER PIÈCES JOINTES',
                'required' 	=> true,
//                'attr' => ['hidden'=> ''],
                'constraints' => array(
                    new File(),
                )])
            ->add('infoPj',EntityType::class, array(
                'label' => $this->trans->trans('label.infoPj'),
                'class' => InformationPj::class,
                'choice_label' => 'libelle'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PjCloture::class,
        ]);
    }
}
