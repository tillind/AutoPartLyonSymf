<?php

namespace Base\AutoPartBundle\Controller;

use Base\AutoPartBundle\Business\Voiture;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
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

        $lesVoitures = $this->get("app.requete_base")->getLesVoitures();
        $lesCateg = $this->get("app.requete_base")->getLesCategVoiture();

        //var_dump($lesVoitures);die;
        return $this->render('BaseAutoPartBundle:Default:index.html.twig',
            array(
                "mesVoitures"=>$lesVoitures,
                "lesCategs"=>$lesCateg
            )
        );
    }

    /**
     * @Route("/catalogue")
     */
    public function catalogueAction()
    {

        $lesVoitures = $this->get("app.requete_base")->getLesVoitures();
        $lesCateg = $this->get("app.requete_base")->getLesCategVoiture();

        //var_dump($lesVoitures);die;
        return $this->render('BaseAutoPartBundle:Default:index.html.twig',
            array(
                "mesVoitures"=>$lesVoitures,
                "lesCategs"=>$lesCateg
            )
        );
    }

    /**
     * @Route("/catalogue/{id}")
     */
    public function catalogueByCategAction($id=null)
    {

        $lesVoitures = $this->get("app.requete_base")->getLesVoituresByCateg($id);
        $lesCateg = $this->get("app.requete_base")->getLesCategVoiture();

        return $this->render('BaseAutoPartBundle:Default:index.html.twig',
            array(
                "mesVoitures"=>$lesVoitures,
                "lesCategs"=>$lesCateg
            )
        );
    }

    /**
     * @Route("/connect")
     */
    public function connectAction(Request $request){
        $formBuilder = $this->get('form.factory')->createBuilder();
        $formBuilder
            ->add('Login','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('Password','Symfony\Component\Form\Extension\Core\Type\PasswordType')
            ->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType');

        $form = $formBuilder->getForm();


        if ($form->isValid()) {
              // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
        }

        return $this->render('BaseAutoPartBundle:Default:connection.html.twig',array(
            'form' => $form->createView()
        ));

    }
    /**
     * @Route("/insc")
     */
    public function inscriptionAction(){
        return $this->render('BaseAutoPartBundle:Default:inscription.html.twig');
    }
}
