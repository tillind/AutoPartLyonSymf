<?php

namespace Base\AutoPartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


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
        if($this->get('session')->isStarted()){
            $type = $this->get('session')->get('type');
            if ($type == 'utilisateur') {
                return $this->redirectToRoute('client_autopart_default_index');
            } elseif ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }
        }

        $lesVoitures = $this->get("app.requete_base")->getLesVoitures();
        $lesCateg = $this->get("app.requete_base")->getLesCategVoiture();

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
        if($this->get('session')->isStarted()){
            $type = $this->get('session')->get('type');
            if ($type == 'utilisateur') {
                return $this->redirectToRoute('client_autopart_default_index');
            } elseif ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }
        }

        $lesVoitures = $this->get("app.requete_base")->getLesVoitures();

        $lesCateg = $this->get("app.requete_base")->getLesCategVoiture();

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


        if($this->get('session')->isStarted()){
            $type = $this->get('session')->get('type');

            if ($type == 'utilisateur') {
                return $this->redirectToRoute('client_autopart_default_index');
            } elseif ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }
        }

        $formBuilder = $this->get('form.factory')->createBuilder();
        $formBuilder
            ->add('Login','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('Password','Symfony\Component\Form\Extension\Core\Type\PasswordType')
            ->add('submit','Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->add('TypeUser','Symfony\Component\Form\Extension\Core\Type\ChoiceType',array(
                'choices' => array('Utilisateur' => 'u', 'Employé' => 'e'),
            ));

        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $typeUtilisateur =$data['TypeUser'];
            $res =  null;

            if($typeUtilisateur=='u'){
               $res = $this->get("app.requete_base")->userConnexion($data['Login'],$data['Password']);
                if(is_null($res)){
                    $this->get('session')->getFlashBag()->set('erreur', 'Combinaison utilisateur/mot de passe inconnue');
                }else{
                    $session = $request->getSession();
                    $session->start();
                    $session->set('user',$res);
                    $session->set('type','utilisateur');
                    return $this->redirectToRoute("client_autopart_default_index");
                }
            }else if($typeUtilisateur=='e'){
                $res =  $this->get("app.requete_base")->employeConnexion($data['Login'],$data['Password']);
                if(is_null($res)){
                    $this->get('session')->getFlashBag()->set('erreur', 'Combinaison utilisateur/mot de passe inconnue');
                }else{
                    $session = $request->getSession();
                    $session->start();
                    $session->set('user',$res);
                    $session->set('type','employe');
                    return $this->redirectToRoute("employe_autopart_default_index");
                }
            }else{
                $this->get('session')->getFlashBag()->set('erreur', 'erreur d\'origine inconnue');
            }

        }

        return $this->render('BaseAutoPartBundle:Default:connection.html.twig',array(
            'form' => $form->createView()
        ));

    }
    /**
     * @Route("/insc")
     */
    public function inscriptionAction(Request $request){
        if($this->get('session')->isStarted()){
            $type = $this->get('session')->get('type');
            if ($type == 'utilisateur') {
                return $this->redirectToRoute('client_autopart_default_index');
            } elseif ($type == 'employe') {
                return $this->redirectToRoute('employe_autopart_default_index');
            }
        }

        $formBuilder= $this->get("form.factory")->createBuilder();
        $formBuilder->add('login','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('pass','Symfony\Component\Form\Extension\Core\Type\PasswordType')
            ->add('repPass','Symfony\Component\Form\Extension\Core\Type\PasswordType')
            ->add('nom','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('prenom','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('mail','Symfony\Component\Form\Extension\Core\Type\EmailType')
            ->add('tel','Symfony\Component\Form\Extension\Core\Type\NumberType')
            ->add('adresse','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('birth','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('permis','Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('save','Symfony\Component\Form\Extension\Core\Type\SubmitType');

        $form= $formBuilder->getForm();

        $form->handleRequest($request);

        $param ="display:none";
        if ($form->isSubmitted()) {
            $data = $form->getData();
            if($data['pass'] != $data['repPass']){
                $this->get('session')->getFlashBag()->set('erreur', 'Les mots de passes ne sont pas identique');
            }else{
                $result = $this->get("app.requete_base")->userInscription($data);
                if($result == 2){
                    $this->get('session')->getFlashBag()->set('erreur', 'L\'email existe deja');
                } else if($result ==1) {
                    $this->get('session')->getFlashBag()->set('success', 'L\'inscription à étais effectué avec succée vous pouvez désormer vous connecter');
                    return $this->redirectToRoute("base_autopart_default_index");
                }else{
                    $this->get('session')->getFlashBag()->set('erreur', 'Quelque chose de mal s\'est passé');

                }
            }
            $param="";
        }

        return $this->render('BaseAutoPartBundle:Default:inscription.html.twig',array(
            'form' => $form->createView(),
            'param'=>$param
        ));
    }
    /**
     * @Route("/disconnect")
     */
    public function disconnectAction(Request $request){
        $this->get('session')->invalidate();

        return $this->redirectToRoute("base_autopart_default_index");
    }
}
