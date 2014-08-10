<?php

namespace Magice\Bundle\ClientUserBundle\Controller;

use Magice\Bundle\ClientUserBundle\DependencyInjection\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    private function getAlreadyLoginUrl(Request $request)
    {
        $url = null;

        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $url = $request->getSchemeAndHttpHost();
            if ($target = $this->container->getParameter('magice.client.user.already_logedin_redirect_target')) {
                $url = $target;
            }
        }

        return $url;
    }


    public function indexAction()
    {
        $name = $this->get('security.context')->getToken()->getUsername();;

        return $this->render('MagiceClientUserBundle:Default:index.html.twig', array('name' => $name));
    }

    public function loginAction(Request $request)
    {
        $url = $this->getAlreadyLoginUrl($request)
            ? : $this->get('router')->generate(
                'magice_client_user_connect',
                array('service' => Configuration::RESOURCE_NAME)
            );

        return new RedirectResponse($url);
    }

    public function registerAction(Request $request)
    {
        $url = $this->getAlreadyLoginUrl($request)
            ? : $this->container->getParameter('magice.client.uesr.register_url');

        return new RedirectResponse($url);
    }
}
