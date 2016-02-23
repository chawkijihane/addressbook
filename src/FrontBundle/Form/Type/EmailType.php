<?php

namespace FrontBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EmailFormType
 * @package FrontBundle\Form\Type
 */
class EmailType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Nom et prénom',
                    'class' => 'form-control',
                ),
            ))
            ->add('email', 'email', array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Email',
                    'class' => 'form-control',
                ),
            ))
        ;

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\Email',
            'intention' => 'email_form',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'email_form_type';
    }
}
