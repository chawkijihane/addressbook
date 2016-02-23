<?php

namespace FrontBundle\Controller;

use CoreBundle\Controller\Controller;

use CoreBundle\Entity\Email;
use CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontController extends Controller
{

    public function indexAction(Request $request)
    {
        return $this->redirect($this->generateUrl('front_address_index'));
    }


}
