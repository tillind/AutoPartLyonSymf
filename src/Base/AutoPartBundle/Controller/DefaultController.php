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
        $maVoiture[] = new Voiture(1,"Scenic");
        $maVoiture[] = new Voiture(2,"BMW");
        $maVoiture[] = new Voiture(3,"Tesla");
        $maVoiture[] = new Voiture(4,"Mercedes");
        $maVoiture[] = new Voiture(5,"Totoya");



        return $this->render('BaseAutoPartBundle:Default:index.html.twig',
            array(
                "mesVoitures"=>$maVoiture
            )
        );
    }
}
