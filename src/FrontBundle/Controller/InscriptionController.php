<?php

namespace FrontBundle\Controller;

use CoreBundle\Controller\Controller;

use CoreBundle\Entity\Email;
use CoreBundle\Event\FormEvent;
use CoreBundle\Event\UserEvent;
use CoreBundle\Util\SlugHandler;
use FrontBundle\Form\Type\ProfilInscriptionFormType;
use Symfony\Component\HttpFoundation\Request;


class InscriptionController extends Controller
{

    /**
     * Display form to complete profil
     *
     * @param $request Request
     * @return Response
     */
    public function createProfilAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();
        $form = $this->createForm ( new ProfilInscriptionFormType($em, $user), $user,  array('validation_groups' => array('EditProfile'),));
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $setRegistered = $this->getEmailRepository()->findOneBy(array(
                'email' => $data->getEmail()
            ));
            if(!empty($setRegistered)) {
                $this->getEmailRepository()->updateStateByEmail($data->getEmail(), Email::STATE_REGISTERED);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);

            $user->setIp($request->getClientIp());

            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('front_address_index'));
        }

        return $this->render('FrontBundle:inscription:profil.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}
