<?php

namespace FrontBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class MenuBuilder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-tabs nav-stacked main-menu');

        $menu->addChild('address_index', array(
            'route' => 'front_address_index',
            'label' => "Carnets d'addresses",
            'extras' => array(
                'icon' => 'fa fa-users',
                'span_label' => "Carnet d'adresses",
                "span_label_notif" => "",
            )
        ));

        return $menu;
    }
}
