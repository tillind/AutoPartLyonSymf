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
        if($this->get('session')->isStarted()&& is_null($this->get('session')->get('type'))){
            $type = $this->get('session')->get('type');
            if ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }

        }else {
            return $this->redirectToRoute('base_autopart_default_index');
        }

        $login = $this->get('session')->get('user')['login'];

        /*var_dump($this->get('session')->get('user'));
        die;*/

        $lesReservations = $this->get("app.requete_client")->getReservation($login);
/*        $formBuilder = $this->get('form.factory')->createBuilder();
        $formBuilder->add('etatDesLieux','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType');

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data= $form->getData();
            $etatDesLieux= $data['etatDesLieux'];
        }*/

        return $this->render('ClientAutoPartBundle:Default:consulterResa.html.twig',
            array(
                "mesReservations"=>$lesReservations,
            //    'form' => $form->createView()

            )
        );



    }


    /**
     * @Route("/etatdeslieux/{idreservation}")
     */
    public function etatdeslieuxAction($idreservation=null,Request $request)
    {
        if($this->get('session')->isStarted()){
            $type = $this->get('session')->get('type');


            if ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }

        }else {
            return $this->redirectToRoute('base_autopart_default_index');
        }

        $login = $this->get('session')->get('user')['login'];
        //$this->get("app.requete_client")->annulerReservation($idreservation);
        //$lesReservations=null;

        //$lesReservations = $this->get("app.requete_client")->getReservation($login);
        $formBuilder = $this->get('form.factory')->createBuilder();
        $formBuilder->add('etatDesLieux','Symfony\Component\Form\Extension\Core\Type\TextareaType')
            ->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType');

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data= $form->getData();
            $etatDesLieux= $data['etatDesLieux'];
            return $this->render('ClientAutoPartBundle:Default:consulterResa.html.twig',
                array(
                    "mesReservations" => $this->get("app.requete_client")->getReservation($login)
                )
            );

        }
        return $this->render('ClientAutoPartBundle:Default:etatDesLieux.html.twig',
            array(
                "mesReservations" => $this->get("app.requete_client")->getReservation($login),
                'form' => $form->createView()
            )
        );



    }


    /**
     * @Route("/annuler/{idreservation}")
     */
    public function annulerAction($idreservation=null)
    {
        if($this->get('session')->isStarted()){
            $type = $this->get('session')->get('type');


            if ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }

        }else {
            return $this->redirectToRoute('base_autopart_default_index');
        }

        $login = $this->get('session')->get('user')['login'];
        $this->get("app.requete_client")->annulerReservation($idreservation);
        //$lesReservations=null;
        return $this->render('ClientAutoPartBundle:Default:consulterResa.html.twig',
            array(
                "mesReservations" => $this->get("app.requete_client")->getReservation($login)
            )
        );



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
    public function consulerVoitureAction(Request $request, $id=null)
    {
        //informations de la voiture à afficher
        $maVoiture = $this->get("app.requete_client")->getVoitureById($id);
        $indispo = $this->get("app.requete_client")->getIndispoById($id);
        $lesStations = $this->get("app.requete_client")->getLesStations();


        //création du formulaire (juste un bouton)
        $formBuilder = $this->get('form.factory')->createBuilder();
        $formBuilder->add('depart', ChoiceType::class, array(
            'choices'  => $lesStations,
            ))
        ->add('arrivee', ChoiceType::class, array(
            'choices'  => $lesStations,
            ))
        ->add('dateDebut','Symfony\Component\Form\Extension\Core\Type\TextType')
        ->add('dateFin','Symfony\Component\Form\Extension\Core\Type\TextType')
        ->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType');
        $form = $formBuilder->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            echo "ok";   
        }

        return $this->render('ClientAutoPartBundle:Default:ficheVehicule.html.twig',
            array(
                'maVoiture'=>$maVoiture,
                'indispo'=>$indispo,
                'form' => $form->createView()
            ));
    }
}
