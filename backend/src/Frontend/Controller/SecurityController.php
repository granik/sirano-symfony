<?php

namespace App\Frontend\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $parameters = ['last_username' => $lastUsername, 'error' => $error];
        
        if ($request->query->has('_target_path')) {
            $parameters['targetPath'] = $request->query->get('_target_path');
        }
        
        return $this->render('frontend/login.html.twig', $parameters);
    }
}
