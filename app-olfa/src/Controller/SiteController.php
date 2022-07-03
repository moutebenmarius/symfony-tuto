<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Entity\Eleve;
use App\Entity\Evaluation;
use App\Entity\Matiere;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="app_site")
     */
    public function index(): Response
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/evaluation/eleve", name="evaluer_enseignant")
     */
    public function evaluation()
    {
        $eleve = $this->getUser();
        $eleveCin = $eleve->getCin();
        $eleveData = $this->getDoctrine()->getRepository(Eleve::class)->findOneBy(['cinParent' => $eleveCin]);
        $classe = $eleveData->getClasse();
        $allAffectations  = $this->getDoctrine()->getRepository(Affectation::class)->findBy(['classe' => $classe, 'estArchive' => false]);
        $data = [];
        foreach ($allAffectations as $affectation) {

            array_push($data, ['enseignant' => $affectation->getEnseignant(), 'matiere' => $affectation->getMatiere()]);
        }

        return $this->render('site/evaluation.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * @Route("/evaluation/enseignant/{id}", name="evaluer_enseignant_matiere")
     */
    public function evaluationEnseignant(Request $request, Utilisateur $enseignant)
    {
        $eleveConnecte = $this->getUser();
        $eleveData     = $this->getDoctrine()->getRepository(Eleve::class)->findOneBy(['cinParent' => $eleveConnecte->getCin()]);
        $classeEleve   = $eleveData->getClasse();
        $affectation   = $this->getDoctrine()->getRepository(Affectation::class)->findOneBy(['enseignant' => $enseignant, 'classe' => $classeEleve]);
        $matiere       = $affectation->getMatiere();

        $form = $this->createFormBuilder(null)->add('remarque', ChoiceType::class, [
            'attr' => ['class' => 'form-control'],
            'choices' => [
                'Trés Satisfait' => 'Trés Satisfait',
                'Satisfait' => 'Satisfait',
                'Neutre' => 'Neutre',
                'Pas Satisfait' => 'Pas Statisfait',
                'Pas de tous Satisfait' => 'Pas de tous Satisfait',
            ],
            'expanded' => true
        ])->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $valeur = $form->get('remarque')->getData();
            $evalutation = new Evaluation();
            $evalutation->setEnseignant($enseignant);
            $evalutation->setMatiere($matiere);
            $evalutation->setValeur($valeur);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($evalutation);
            $manager->flush();
            return $this->redirectToRoute('evaluer_enseignant');
        }
        return $this->render('site/evaluer-ens.html.twig', ['form' => $form->createView()]);
    }



    /**
     * @Route("/about", name="app_about")
     */
    public function about(): Response
    {
        return $this->render('site/about.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(): Response
    {
        return $this->render('site/contact.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    private function orangeAuthentification()
    {

        $url = "https://api.orange.com/oauth/v3/token";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Authorization: Basic OVJQWVhrV0c5TWZROU9uQUxBRmNZdmVCcVU3TG81SWQ6WEhnVEpVZkw0RktFYXRPcw==",
            "Content-Type: application/x-www-form-urlencoded",
            "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = "grant_type=client_credentials";

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        $orangeData = json_decode($resp);
        curl_close($curl);
       return $orangeData;
    }

    private function sendSMStoNumber($text, $number, $access_token)
    {
        $url = "https://api.orange.com/smsmessaging/v1/outbound/tel%3A%2B54573074/requests";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Authorization: Bearer $access_token",
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $tel = "21627820155";
        $data = [
            'outboundSMSMessageRequest' => [
                'address' => "tel:+216" . intval($number),
                'senderAddress' => "tel:+54573074",
                'outboundSMSTextMessage' => [
                    "message" => $text
                ]
            ]
        ];

        $json = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        var_dump($resp);
        curl_close($curl);
        
        die();
        var_dump($resp);
    }
    /**
     * @Route("/mot-de-passe-oublie", name="mot_de_passe_oublie")
     */
    public function motdePasseoublie(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createFormBuilder(null)->add('email', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dd($form->get('email')->getData());
            $email = $form->get('email')->getData();
            $existe = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);
            $telephone = $existe->getTelephone();
            $newPassword = uniqid();
            $hash = $encoder->encodePassword($existe, $newPassword);
            $manager = $this->getDoctrine()->getManager();
            $existe->setPassword($hash);
            $manager->flush();
            $result = $this->orangeAuthentification();
            $access_token = $result->access_token;
            $dataReceived = $this->sendSMStoNumber("Votre nouveau mot de passe est: $newPassword", $telephone,$access_token);
            $this->addFlash("info","Vérifier votre numéro");
            return $this->redirectToRoute('mot_de_passe_oublie');
            /*$email = trim($form->get('email')->getData());
            $db = mysqli_connect('localhost','jihed', 'ideveloper', 'app_ennour_09');
            $query = mysqli_query($db, "SELECT * FROM utilisateur WHERE email = \"".$email."\"");
            if(mysqli_num_rows($query)> 0){
                $user  = mysqli_fetch_assoc($query);
               // 627fe1191dc7c
                $id    = $user['id'];
               // dd($id);
                $numero = $user['telephone'];
                $genPassword = mt_rand(10000,20000);
                
                $us  = new Utilisateur();
                $hash = $encoder->encodePassword($us, $genPassword);
                
                $newPassword = $us->setPassword($hash);
                $updateMotdepasse = "UPDATE utilisateur SET password = \"".$newPassword."\"  WHERE id = $id";
                $upd = mysqli_query($db, $updateMotdepasse);
                if($upd){

                   
                }
                //dd($numero);
            } */
           
            
        }
        return $this->render('site/mot-de-passe-oublie.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
