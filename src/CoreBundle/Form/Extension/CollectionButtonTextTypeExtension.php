<?php

namespace CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionButtonTextTypeExtension extends AbstractTypeExtension
{
    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['add_button_text'] = $options['add_button_text'];
        $view->vars['remove_button_title'] = $options['remove_button_title'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'add_button_text' => null,
            'remove_button_title' => null,
        ));
    }

    public function getExtendedType()
    {
        return 'collection';
    }
}
