<?php

namespace FrontBundle\Controller;

use CoreBundle\Controller\Controller;

use CoreBundle\Entity\Email;
use CoreBundle\Entity\User;
use CoreBundle\Event\FormEvent;
use CoreBundle\Event\UserEvent;
use spec\Gaufrette\Adapter;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Gaufrette\Adapter\Local as LocalAdapter;


class CompteController extends Controller
{
    /**
     * Display form to edit profil
     *
     * @param $request Request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm('profil_form_type', $user, array('validation_groups' => array('EditProfil')));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $dispatcher = $this->getDispatcher();

            $dispatcher->dispatch(UserEvent::USER_EDIT_SUCCESS, new FormEvent($form));
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Votre profil a été mis à jour.');

            return $this->redirect($this->generateUrl('front_compte_index'));
        }

        return $this->render('FrontBundle:compte:index.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }
}
