<?php

namespace Client\AutoPartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/client")
     */
    public function indexAction()
    {
        return $this->render('ClientAutoPartBundle:Default:index.html.twig');
    }
}
