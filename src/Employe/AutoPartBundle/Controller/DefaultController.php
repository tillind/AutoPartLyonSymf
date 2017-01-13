<?php

namespace Employe\AutoPartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
        $lesTypesVoitures = $this->get("app.requete_employe")->getLesTypesVoitures();
        $formBuilder = $this->get('form.factory')->createBuilder();
        foreach ($lesStations as $station) {
            $lesStationChoice[$station["nom"]] = $station["id"];
        }
        foreach ($lesTypesVoitures as $voiture) {
            $lesTypesVoituresChoice[$voiture["libelle"]] = $voiture["code"];
        }

        $formBuilder
            ->add('etatvoiture', TextType::class)
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
                    'choices' => $lesTypesVoituresChoice,
                ))
            ->add('nomvehicule', TextType::class)
            ->add('submit', SubmitType::class);


        $form = $formBuilder->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();

            $result = $this->get("app.requete_employe")->ajoutVehicule($data);
            var_dump($data);
            die;
            return $this->redirectToRoute('task_success');
        }

        return $this->render('EmployeAutoPartBundle:Default:ajout.html.twig', array(
            "mesStations" => $lesStations,
            "mesVoitures" => $lesTypesVoitures,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/recherche")
     */
    public function rechercheAction(Request $request)
    {
        $lesCategories = null;
        $lesCategories= $this->get("app.requete_employe")->getLesCategVoiture();

        return $this->render('EmployeAutoPartBundle:Default:recherche.html.twig', array(
            "mesCategories" => $lesCategories,
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



