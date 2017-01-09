<?php

namespace Client\AutoPartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class DefaultController extends Controller
{
    /**
     * @Route("/client")
     */
    public function indexAction()
    {
        return $this->render('ClientAutoPartBundle:Default:index.html.twig');
    }

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
     * @Route("/recherche")
     */
    public function rechercheAction(){
            //$a=date_create('13-02-2013');
            //$b=date_create('13-02-2015');
            //$test = $this->get("app.requete_base")->getLesVoitureByDate($a, null, $b, null); 

        // Récupération des catégories de voiture 
        $result = $this->get("app.requete_client")->getLesCategVoiture();
        $lesCategories =array();            
        foreach($result as $uneCateg){
            $lesCategories[$uneCateg['lib']]= $uneCateg['cat'];
        }
        $lesCategories["Pas de préférence"]=null; //valeur par défaut

        $formBuilder = $this->get('form.factory')->createBuilder();
        $formBuilder->add('categorie', ChoiceType::class, array(
            'choices'  => $lesCategories,
            ))
        ->add('dateDebut','Symfony\Component\Form\Extension\Core\Type\TextType')  //TO DO: mettre pas obligatoire
        ->add('dateFin','Symfony\Component\Form\Extension\Core\Type\TextType')
        ->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType');

        $form = $formBuilder->getForm();


        if ($form->isValid()) {
            //TO DO: vérifier que dateFin> dateDebut
            //TO DO: renvoyer vers la liste des voitures qui correspondent
        }

        return $this->render('ClientAutoPartBundle:Default:recherche.html.twig',array(
            'form' => $form->createView()
            ));

    }
}
