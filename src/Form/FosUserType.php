<?php

namespace App\Form;

use App\Entity\Fonction;
use App\Entity\FosUser;
use App\Entity\Profil;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatorInterface;

class FosUserType extends AbstractType
{
    private $trans;
    private $auth;
    private $user;
    public function __construct(TranslatorInterface $translator, AuthorizationCheckerInterface $checker, Security $security)
    {
        $this->trans = $translator;
        $this->auth = $checker;
        $this->user = $security->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'required' => true,
                'label' => $this->trans->trans('label.nom'),
                'attr' => ['class' => 'input-entite-name'],
            ))
            ->add('lastname', null, array(
                'required' => true,
                'label' => $this->trans->trans('label.prenom')
            ))
            ->add('email', EmailType::class, array(
                'label' => $this->trans->trans('label.email'),
                'required' => true
            ))
            ->add('prefixPhone', null, array(
                'required' => true,
                'attr' => ['maxlength' => 5]
            ))
            ->add('phoneNumber', null, array(
                'required' => true,
                'attr' => array('maxlength' => 10, 'minlength' => 7),
                'label' => $this->trans->trans('label.tel')
            ))
            ->add('actif', null, array(
                'label' => $this->trans->trans('label.enable')
            ))
            ->add('societe', EntityType::class, array(
                'class' => 'App\Entity\Societe',
                'label' => $this->trans->trans('label.societe'),
                'choice_label' => 'libele',
                'attr' => array('class' => 'custom-select'),
                'required' => true,
                'placeholder' => $this->trans->trans('label.choice.societe'),
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getSocieteByRole($this->user);
                }
            ))
            ->add('profile', EntityType::class, array(
                'class' => Profil::class,
                'label' => 'PROFIL :',
                'choice_label' => 'libele',
                'attr' => array('class' => 'custom-select'),
                'required' => true,
                'placeholder' => $this->trans->trans('label.choose.fonction'),
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getProfileByAdmin($this->auth->isGranted(('ROLE_SUPERADMIN')),$this->auth->isGranted(('ROLE_ADMIN')),$this->auth->isGranted(('ROLE_JURISTE')));
                }
            ))
           /* ->add('fonction', EntityType::class, array(
                'class' => Fonction::class,
                'choice_label' => 'libele',
                'attr' => array('class' => 'custom-select'),
                'required' => true,
                'placeholder' => $this->trans->trans('label.choose.fonction'),
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->getProfileByAdmin($this->auth->isGranted(('ROLE_SUPERADMIN')),$this->auth->isGranted(('ROLE_ADMIN')),$this->auth->isGranted(('ROLE_JURISTE')));
                }
            ))*/
           ;
        if ($options['remove_field']) {
            $builder
                ->remove('name')
                ->remove('lastname')
                ->remove('email')
                ->remove('prefixPhone')
                ->remove('phoneNumber')
                ->remove('actif')
                ->remove('societe')
                ->remove('profile')
                ->remove('fonction');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FosUser::class,
            'remove_field' => false
        ]);
    }
}
