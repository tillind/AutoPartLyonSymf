<?php

namespace Employe\AutoPartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        if ($this->get('session')->isStarted()) {
            $type = $this->get('session')->get('type');
            if ($type == 'utilisateur') {
                return $this->redirectToRoute('client_autopart_default_index');
            } elseif ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }
        }
        return $this->render('EmployeAutoPartBundle:Default:index.html.twig',
            array()
        );
    }

    /**
     * @Route("/ajout")
     */
    public function ajoutAction(Request $request)
    {
        $lesStationChoice = null;
        $lesTypesVoituresChoice = null;
        $lesStations = $this->get("app.requete_employe")->getLesStations();
        $lesCategories = $this->get("app.requete_employe")->getLesCategVoiture();
        $formBuilder = $this->get('form.factory')->createBuilder();
        foreach ($lesStations as $station) {
            $lesStationChoice[$station["nom"]] = $station["id"];
        }
        $formBuilder
            ->add('etatvoiture', ChoiceType::class, array(
                'choices'  => array(
                    'Bon' => 'Bon',
                    'En panne' => 'En panne',
                    'A supprimer' => 'Supprimer',
                ),
            ))
            ->add('datedebutassurance', TextType::class)
            ->add('datefinassurance', TextType::class)
            ->add('nbkilometres', TextType::class)
            ->add('numcartegrise', TextType::class)
            ->add("idstation", ChoiceType::class,
                array(
                    "attr" => array("class" => "form-control select2"),
                    'choices' => $lesStationChoice,
                ))
            ->add('codevoiture', ChoiceType::class,
                array(
                    "attr" => array("class" => "form-control select2"),
                    'choices' => $lesCategories,
                ))
            ->add('nomvehicule', TextType::class)
            ->add('submit', SubmitType::class);


        $form = $formBuilder->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $result = $this->get("app.requete_employe")->ajoutVehicule($data);
            return $this->render('EmployeAutoPartBundle:Default:ajout.html.twig', array(
                "mesStations" => $lesStations,
                "mesVoitures" => $lesCategories,
                'form' => $form->createView()
            ));
        }

        return $this->render('EmployeAutoPartBundle:Default:ajout.html.twig', array(
            "mesStations" => $lesStations,
            "mesVoitures" => $lesCategories,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/recherche")
     */
    public function rechercheAction(Request $request)
    {
        // Récupération des catégories de voiture

        $lesCategories = $this->get("app.requete_employe")->getLesCategVoiture();
        $lesCategories["Pas de préférence"]=null; //valeur par défaut

        //création du formulaire
        $formBuilder = $this->get('form.factory')->createBuilder();
        $formBuilder->add('categorie', ChoiceType::class, array(
            'choices'  => $lesCategories,
        ))
            ->add('dateDebut',TextType::class,array('required' => false))
            ->add('dateFin',TextType::class,array('required' => false))
            ->add('submit',SubmitType::class);
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
            $lesVoitures = $this->get("app.requete_employe")->getLesVoitureByDateAndCateg($dateDebut,$dateFin,$cat);
        }

        //affichage du formulaire
        return $this->render('EmployeAutoPartBundle:Default:recherche.html.twig',
            array(
                'form' => $form->createView(),
                'lesVoitures'=>$lesVoitures
            ));

    }
    /**
     * @Route("/voiture/{id}")
     */
    public function voitureByIdAction($id=null)
    {
        if($this->get('session')->isStarted()){
            $type = $this->get('session')->get('type');
            if ($type == 'utilisateur') {
                return $this->redirectToRoute('client_autopart_default_index');
            } elseif ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }
        }
        $lesVoitures = $this->get("app.requete_employe")->getLesVoituresById($id);


        return $this->render('EmployeAutoPartBundle:Default:ficheVehicule.html.twig',
            array(
                "mesVoitures"=>$lesVoitures,
            ));
    }
    /**
     * @Route("/modifVoiture/{id}")
     */
    public function modifVoitureByIdAction(Request $request,$id=null)
    {
        $lesStationChoice = null;
        $lesTypesVoituresChoice = null;
        $lesDonneesPreRemplies=null;
        $dates=null;
        $lesStations = $this->get("app.requete_employe")->getLesStations();
        $lesTypesVoitures = $this->get("app.requete_employe")->getLesCategVoiture();
        $lesDonneesPreRemplies = $this->get("app.requete_employe")->getLesVoituresById($id);
        foreach ($lesStations as $station) {
            $lesStationChoice[$station["nom"]] = $station["id"];
        }

        foreach ($lesDonneesPreRemplies[0] as $voiture) {
            $dates = $this->get("app.requete_employe")->getDateAsString("datedebutassurance","datefinassurance",$voiture->getDatedebutassurance());
        }
        $formBuilder = $this->get('form.factory')->createBuilder();
        foreach ($lesDonneesPreRemplies[0] as $voiture) {
            $formBuilder
                ->add('etatvoiture', ChoiceType::class, array(
                    'choices'  => array(
                        'Bon' => 'Bon',
                        'En panne' => 'En panne',
                        'A supprimer' => 'Supprimer',
                    ),'data' => $voiture->getEtatVoiture()
                ))
                ->add('datedebutassurance', TextType::class,array('data' => $dates['date']))
                ->add('datefinassurance', TextType::class,array('data' => $dates['date2']))
                ->add('nbkilometres', TextType::class, array('data' => $voiture->getNbkilometre()))
                ->add('numcartegrise', TextType::class,array('data' => $voiture->getNumcartegrise()))
                ->add("idstation", ChoiceType::class,
                    array(
                        'data' => $lesStationChoice[$lesDonneesPreRemplies[1][0]['nom']],
                        "attr" => array("class" => "form-control select2"),
                        'choices' => $lesStationChoice,

                    ));
                $formBuilder->add('codevoiture', ChoiceType::class,
                    array(
                        "attr" => array("class" => "form-control select2"),
                        'choices' => $lesTypesVoitures,

                    ))
                ->add('nomvehicule', TextType::class,array('data' => $voiture->getnomVoiture()))
                ->add('idvehicule', TextType::class,array('data' => $voiture->getIdVoiture()))
                ->add('submit', SubmitType::class);

        }
        $form = $formBuilder->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();

            $this->get("app.requete_employe")->modifVehicule($data);

            $lesVoitures = $this->get("app.requete_employe")->getLesVoituresById($id);


            return $this->render('EmployeAutoPartBundle:Default:ficheVehicule.html.twig',
                array(
                    "mesVoitures"=>$lesVoitures,
                ));
        }

        return $this->render('EmployeAutoPartBundle:Default:modifVehicule.html.twig', array(
            "mesStations" => $lesStations,
            "mesVoitures" => $lesTypesVoitures,
            "mesDonnesPreRemplies" => $lesDonneesPreRemplies,
            "mesStationsChoisies" => $lesStationChoice,
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/catalogue/{id}")
     */
    public function catalogueByCategAction($id=null)
    {
        if($this->get('session')->isStarted()){
            $type = $this->get('session')->get('type');
            if ($type == 'utilisateur') {
                return $this->redirectToRoute('client_autopart_default_index');
            } elseif ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }
        }
        $lesVoitures = $this->get("app.requete_base")->getLesVoituresByCateg($id);
        $lesCateg = $this->get("app.requete_base")->getLesCategVoiture();
        return $this->render('EmployeAutoPartBundle:Default:catalogue.html.twig',
            array(
                "mesVoitures"=>$lesVoitures,
                "lesCategs"=>$lesCateg
            )
        );
    }
}



