<?php

namespace FrontBundle\Controller;

use CoreBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CoreBundle\Util;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use CoreBundle\Entity\Email;
use CoreBundle\Entity\User;
use CoreBundle\Event\FormEvent;
use CoreBundle\Event\AddressEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AddressController extends Controller
{

    /**
     * Lists all address
     *
     * @param $request Request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        if(!empty($user))
            $user_id = $user->getId();
        else
            $user_id = null;
        $emails = $this->getEmailRepository()->findBy(array(
            'user' => $user_id
        ));

        return $this->render('FrontBundle:Address:index.html.twig', array(
            'emails' => $emails,
            'user' => $user
        ));
    }

    /**
     * Displays the form to add a Address
     *
     * @param $request Request
     * @return Response
     *
     */
    public function addAction(Request $request)
    {
        $email = new Email();

        $user = $this->getUser();
        $form = $this->createForm('address_form_type', $email, array('validation_groups' => array('AddAddress')));

        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();
            $contact = $data->getEmail();
            $isEmailRegistered = $this->getUserRepository ()->findOneBy ( array (
                'email' => $contact
            ));
            if(!empty($isEmailRegistered))
                $email->setState(Email::STATE_REGISTERED);
            $em = $this->getDoctrine()->getManager();
            $dispatcher = $this->getDispatcher();

            $email->setUser($this->getUser());

            $dispatcher->dispatch(AddressEvent::ADDRESS_ADD_SUCCESS, new FormEvent($form));
            $em->persist($email);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', "Le contact a été ajouté à votre carnet d'adresses.");

            return $this->redirect($this->generateUrl('front_address_index'));
        }

        return $this->render('FrontBundle:Address:add.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * Show contact
     *
     * @param $address Email
     * @return Response
     */
    public function showAction(Email $address)
    {
        $user = $this->getUser();
        if (!$address->getId()) {
            throw $this->createNotFoundException('Page non trouvé');
        }

        $address = $this->getEmailRepository()->findOneBy(array(
            'id' => $address->getId()
        ));

        return $this->render('FrontBundle:Address:show.html.twig', array(
            'address' => $address,
            'user' => $user
        ));
    }

    /**
     * Invite contact
     *
     * @param $request Request
     * @param $address Email
     * @return RedirectResponse
     *
     */
    public function inviteAction(Request $request, Email $address)
    {
        if (!$this->getCsrfProvider()->isCsrfTokenValid('address_invite', $request->request->get('_token'))) {
            return $this->redirect($this->generateUrl('front_address_index'));
        }

        $em = $this->getDoctrine()->getManager();
        $address->invite();

        $this->getDispatcher()->dispatch(AddressEvent::ADDRESS_INVITE_SUCCESS, new AddressEvent($address));
        $em->flush();
        $this->getDispatcher()->dispatch(AddressEvent::ADDRESS_INVITE_COMPLETED, new AddressEvent($address));

        $infoMail = array('prenom' => $address->getUser()->getPrenom());
        $from_sender = $this->container->getParameter('prod_email');
        $to_recipient = $this->container->getParameter('contact_email');
        $message = \Swift_Message::newInstance()
            ->setSubject('Invitation à rejoindre AddressBook')
            ->setFrom(array($from_sender => "AddressBook"))
            ->setTo($address->getEmail())
            ->setBcc(array($to_recipient))
            ->setBody($this->get('templating')->render('FrontBundle:Mail:invitation-contact.html.twig', $infoMail),
                'text/html'
            );

        $this->get('mailer')->send($message);

        $this->get('session')->getFlashBag()->add('success', "Un email contenant une invitation a été envoyé au contact.");

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * Delete contact
     *
     * @param $request Request
     * @param $address Email
     * @return RedirectResponse
     *
     */
    public function deleteAction(Request $request, Email $address)
    {
        if (!$this->getCsrfProvider()->isCsrfTokenValid('address_delete', $request->request->get('_token'))) {
            return $this->redirect($this->generateUrl('front_address_index'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($address);

        $this->getDispatcher()->dispatch(AddressEvent::ADDRESS_DELETE_SUCCESS, new AddressEvent($address));
        $em->flush();
        $this->getDispatcher()->dispatch(AddressEvent::ADDRESS_DELETE_COMPLETED, new AddressEvent($address));

        $this->get('session')->getFlashBag()->add('success', "Le contact a été supprimé de votre carnet d'adresse.");

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * Displays the form to edit a contact
     *
     * @param $request Request
     * @param $address Email
     * @return Response
     *
     */
    public function editAction(Request $request, Email $address)
    {
        $user = $this->getUser();
        $form = $this->createForm('address_form_type', $address, array('validation_groups' => array('EditAddress')));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();
            $dispatcher = $this->getDispatcher();
            $dispatcher->dispatch(AddressEvent::ADDRESS_EDIT_SUCCESS, new FormEvent($form));
            $em->flush();
            $dispatcher->dispatch(AddressEvent::ADDRESS_EDIT_COMPLETED, new AddressEvent($address));

            $this->get('session')->getFlashBag()->add('success', "Le contact a été modifié avec succès.");

            return $this->redirect($this->generateUrl('front_address_show', array('id' => $address->getId())));
        }

        return $this->render('FrontBundle:Address:edit.html.twig', array(
            'form' => $form->createView(),
            'address' => $address,
            'user' => $user
        ));
    }
}
