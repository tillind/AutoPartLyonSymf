<?php

namespace Client\AutoPartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    

    /**
     * @Route("/reservation")
     */
    public function reservationAction()
    {
        return $this->render('ClientAutoPartBundle:Default:consulterResa.html.twig');
    }

    /**
     * @Route("/profil")
     */
    public function profilAction()
    {
        //TO DO: créer & changer twig
        return $this->render('ClientAutoPartBundle:Default:consulterResa.html.twig');
    }

    /**
     * @Route("/client")
     */
    public function indexAction(Request $request)
    {
        // Récupération des catégories de voiture

        $lesCategories = $this->get("app.requete_client")->getLesCategVoiture();
        $lesCategories["Pas de préférence"]=null; //valeur par défaut

        //création du formulaire
        $formBuilder = $this->get('form.factory')->createBuilder();
        $formBuilder->add('categorie', ChoiceType::class, array(
            'choices'  => $lesCategories,
            ))
        ->add('dateDebut','Symfony\Component\Form\Extension\Core\Type\TextType',array('required' => false))
        ->add('dateFin','Symfony\Component\Form\Extension\Core\Type\TextType',array('required' => false))
        ->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType');
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

            //TO DO: vérifier que dateFin> dateDebut
            //TO DO: renvoyer vers la liste des voitures qui correspondent
        //récupération des résultats du formulaire
        $lesVoitures =array();
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $dateDebut= $data['dateDebut'];
            $dateFin= $data['dateFin'];
            $cat= $data['categorie'];
            //tableau des résultats des requête pour les dates et la catégorie
            $lesVoitures = $this->get("app.requete_client")->getLesVoitureByDateAndCateg($dateDebut,$dateFin,$cat);
        }

        //affichage du formulaire
        return $this->render('ClientAutoPartBundle:Default:index.html.twig',
            array(
                'form' => $form->createView(),
                'lesVoitures'=>$lesVoitures
            ));

    }


    /**
     * @Route("/client/{id}")
     */
    public function consulerVoitureAction($id=null)
    {
        $maVoiture = $this->get("app.requete_client")->getVoitureById($id);
        return $this->render('ClientAutoPartBundle:Default:ficheVehicule.html.twig',
            array(
                'maVoiture'=>$maVoiture
            ));
    }
}
