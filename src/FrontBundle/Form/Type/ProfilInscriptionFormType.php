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

class ProfilInscriptionFormType extends AbstractType
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
            ->add('telephone', 'text', array(
                'label' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Téléphone',
                ),
            ));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\User',
            'intention' => 'users_profil_registration',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'registration_profil';
    }
}
