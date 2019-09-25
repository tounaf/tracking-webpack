<?php

namespace App\Form;

use App\Entity\InformationPj;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class InformationPjType extends AbstractType
{
    private $trans;
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => $this->trans->trans('label.libelle'),
                'required' => true,
                'attr' => ['requiredMessage' => 'Veuillez renseigner ce champs s\'il vous plait', 'name'=>'libelle'],
            ])
//            ->add('file', FileType::class)
            ->add('isActif', CheckboxType::class, [
                'required' => false,
                'label' => 'Actif',
            ]);
        if ($options['remove_field']) {
            $builder
                ->remove('libelle')
                ->remove('isActif');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InformationPj::class,
            'remove_field' => false
        ]);
    }
}
