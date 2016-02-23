<?php

namespace CoreBundle\Controller;

use CoreBundle\Doctrine\Repository\EmailRepository;
use CoreBundle\Doctrine\Repository\UserRepository;
use CoreBundle\Entity\User;
use CoreBundle\Entity\Email;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

abstract class Controller extends BaseController
{
    /**
     * @return CsrfProviderInterface
     */
    protected function getCsrfProvider()
    {
        return $this->get('form.csrf_provider');
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }

    /**
     * @return SecurityContextInterface
     */
    protected function getSecurityContext()
    {
        return $this->container->get('security.context');
    }

    /**
     * Just type hint the result to be an instance of our User.
     *
     * @return User
     */
    public function getUser()
    {
        return parent::getUser();
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->getDoctrine()->getRepository('CoreBundle:User');
    }

    /**
     * @return EmailRepository
     */
    public function getEmailRepository()
    {
        return $this->getDoctrine()->getRepository('CoreBundle:Email');
    }
}
