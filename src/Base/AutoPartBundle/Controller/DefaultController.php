<?php

namespace Base\AutoPartBundle\Controller;

use Base\AutoPartBundle\Business\Voiture;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {

        $this->get("app.requete_base")->getLesVoitures();


        return $this->render('BaseAutoPartBundle:Default:index.html.twig',
            array(
                "mesVoitures"=>NULL
            )
        );
    }
}
