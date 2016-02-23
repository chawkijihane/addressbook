<?php

namespace FrontBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EmailFormType
 * @package FrontBundle\Form\Type
 */
class EmailFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emails', 'collection', array(
                'type' => new EmailType(),
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'add_button_text' => 'Ajouter un contact',
                'by_reference' => false,
                'attr' => array(
                    'class' => 'with_default',
                    'data-number-to-add' => 1
                ),
                'options' => array(
                    'label' => false,
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
            'data_class' => 'CoreBundle\Entity\User',
            'intention' => 'import_email_form',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'import_email_form_type';
    }
}
