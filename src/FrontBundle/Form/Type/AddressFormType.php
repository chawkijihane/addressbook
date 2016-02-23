<?php

namespace FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;


class AddressFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', 'text', array(
                'label' => 'Prénom',
                'attr' => array(
                    'class' => 'form-control',
                )))
            ->add('nom', 'text', array(
                'label' => 'Nom',
                'attr' => array(
                    'class' => 'form-control',
                )))
            ->add('telephone', 'text', array(
                'label' => "Téléphone",
                'attr' => array(
                    'class' => 'form-control',
                )))
            ->add('email', 'email', array(
                'attr' => array(
                    'class' => 'form-control',
                )))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\Email',
            'intention'  => 'address_form',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'address_form_type';
    }
}
