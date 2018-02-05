<?php

namespace WarehouseBundle\Controller;

use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $authUtils = $this->container->get('security.authentication_utils');

        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('@Warehouse/Warehouse/security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

    }

}