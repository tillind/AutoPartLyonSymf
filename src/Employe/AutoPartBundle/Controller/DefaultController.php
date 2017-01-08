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
    public function indexAction(Request $request)
    {
        $lesStations = $this->get("app.requete_employe")->getLesStations();
        $lesTypesVoitures = $this->get("app.requete_employe")->getLesTypesVoitures();
        foreach ( $lesStations as $station ) {
            $nom[]=$station['nom'];
        }
        foreach ( $lesTypesVoitures as $type ) {
            $libelle[]=$type['libelle'];
        }
        foreach ( $lesTypesVoitures as $c ) {
            $code[]=$c['code'];
        }
        $formBuilder = $this->get('form.factory')->createBuilder();


            $formBuilder
                ->add('etatvoiture', TextType::class)
                ->add('datedebutassurance', TextType::class)
                ->add('datefinassurance', TextType::class)
                ->add('nbkilometres', TextType::class)
                ->add('numcartegrise', TextType::class)
                ->add("idstation", ChoiceType::class,
                    array(
                        "attr" => array("class" => "form-control select2"),
                        'choices' => [$nom],
                        'choice_label' => function ($value, $key, $index) {
                            return $value;
                        },

                    ))
                ->add('codevoiture', ChoiceType::class,
                    array(
                        "attr" => array("class" => "form-control select2"),
                        'choices' => [$libelle],
                        'choice_label' => function ($value, $key, $index) {
                            return $value;
                        },

                    ))
                ->add('submit', SubmitType::class);


            $form = $formBuilder->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();

            $result = $this->get("app.requete_employe")->ajoutVehicule($data);
           var_dump($data);die;
            return $this->redirectToRoute('task_success');
        }

        return $this->render('EmployeAutoPartBundle:Default:index.html.twig',array(
            "mesStations"=>$lesStations,
            "mesVoitures"=> $lesTypesVoitures,
            'form' => $form->createView()
        ));
    }
}
