<?php

namespace FrontBundle\EventListener;

use CoreBundle\Entity\User;
use CoreBundle\Event\FormEvent;
use CoreBundle\Event\UserEvent;
use CoreBundle\Media\Uploader;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UploaderListener implements EventSubscriberInterface
{
    private $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public static function getSubscribedEvents()
    {
        return array(
            UserEvent::USER_EDIT_SUCCESS => 'onUserHandling',
            UserEvent::USER_ADD_SUCCESS => 'onUserAddHandling',
        );
    }

    public function onProjetHandling(FormEvent $event)
    {
        $form = $event->getForm();

        $projet = $form->getData();
        $user = $form->get('user')->getData();

        if (null === $projet) {
            return;
        }

        $photo = $form->get('photoProjetPath')->getData();

        if (null !== $photo) {
            $this->uploader->upload($projet, $photo, 'photoProjetPath');
        }


        $photoProfil = $form->get('user')->get('photoProfilPath')->getData();

        if (null !== $photoProfil) {
            $this->uploader->upload($user, $photoProfil, 'photoProfilPath');
        }
    }
    public function onUserHandling(FormEvent $event)
    {

         $form = $event->getForm();

        /** @var User $user */
        $user = $form->getData();

        if (null === $user) {
            return;
        }


        $photoProfil = $form->get('photoProfilPath')->getData();


        if (null !== $photoProfil) {
            $this->uploader->upload($user, $photoProfil, 'photoProfilPath');
        }
    }

    public function onUserProHandling(FormEvent $event)
    {

        $form = $event->getForm();

        /** @var User $user */
        $user = $form->getData();

        if (null === $user) {
            return;
        }


        $photoProfil = $form->get('photoProfilPath')->getData();


        if (null !== $photoProfil) {
            $this->uploader->upload($user, $photoProfil, 'photoProfilPath','pro');
        }
    }

        public function onUserAddHandling(FormEvent $event)
    {

        $form = $event->getForm();



        /** @var User $user */
        $user = $form->getData();

        if (null === $user) {
            return;
        }



        $photoProfil = $form->get('photoProfilPath')->getData();



        if (null !== $photoProfil) {
            $this->uploader->upload($user, $photoProfil, 'photoProfilPath');
        }


    }
}
