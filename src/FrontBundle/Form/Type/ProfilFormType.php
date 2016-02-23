<?php

namespace FrontBundle\Form\Type;

use CoreBundle\Controller\Controller;
use CoreBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Monolog\Formatter\FormatterInterface;
use Symfony\Component\Form\FormError;

class ProfilFormType extends AbstractType
{

    /**"
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite', 'choice', array(
                'choices' => User::getCiviliteChoices(),
                'label' => false,
                'expanded' => false,
                'multiple' => false,
                'empty_value' => 'Civilité',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('nom', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Nom',
                ),
            ))
            ->add('prenom', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Prénom',
                ),
            ))
            ->add('email', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Email',
                ),
            ))
            ->add('telephone', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Téléphone',
                ),
            ))
            ->add('adresse', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Adresse',
                ),
            ))
            ->add('zip', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Code postal',
                ),
            ))
            ->add('ville', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Ville',
                ),
            ))
            ->add('website', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Site web',
                ),
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\User',
            'intention' => 'profil_form',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'profil_form_type';
    }
}
