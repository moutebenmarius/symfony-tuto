<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Entity\Eleve;
use App\Entity\Matiere;
use App\Entity\PartieMatiere;
use App\Entity\Programme;
use App\Entity\Seance;
use App\Form\PartieMatiereEnseignantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnseignantDController extends AbstractController
{
    /**
     * @Route("/dash-enseignant", name="app_enseignant_d")
     */
    public function index(): Response
    {
        $enseignant = $this->getUser();
        $programme = $this->getDoctrine()->getRepository(Programme::class)->findOneBy(['enseignant' => $enseignant]);
        //dd($programme->getFichierPdf());
        return $this->render('enseignant_d/index.html.twig', [
            'controller_name' => 'EnseignantDController',
            'url'             => $programme->getFichierPdf()
        ]);
    }

    /**
     * @Route("/mes-matieres", name="mes_matieres")
     */
    public function mesMatieres(): Response
    {
        $me = $this->getUser();
        $mesAffectations = $this->getDoctrine()->getRepository(Affectation::class)->findBy(['enseignant'=>$me]);
        $mesMatieres = [];
        
        foreach($mesAffectations as $affectation){
            if($affectation->getMatiere()->getEstArchive() || $affectation->getEstArchive()){
                continue;
            }
            $partieMatiere = $this->getDoctrine()->getRepository(PartieMatiere::class)->findOneBy(['enseignant' =>  $me, 'matiere' => $affectation->getMatiere()]);
            //dd($partieMatiere->getDescription());
            $allow = false;
            if($partieMatiere->getDescription() === "--"){
                $allow = true;
            }else{
                $allow = false;
            }
            array_push($mesMatieres, [ 'matiere' => $affectation->getMatiere(), 'ajouter' => $allow]);
        }
       // dd($mesMatieres);
        $enseignant = $this->getUser();
        $programme = $this->getDoctrine()->getRepository(Programme::class)->findOneBy(['enseignant' => $enseignant]);
        //dd($programme->getFichierPdf());
        //dd($mesMatieres);
        return $this->render('enseignant_d/mes-matieres.html.twig', [
            'matieres' => $mesMatieres,
            'url'             => $programme->getFichierPdf()
        ]);
    }

    /**
     * @Route("/creer-seance/{id}", name="creer_seance")
     */
    public function creerSeance(Matiere $matiere){
        $seance = new Seance();
        $seance->setDateSeance(new \DateTime());
        $seance->setMatiere($matiere);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($seance);
        $manager->flush();
        $enseignant = $this->getUser();
        $programme = $this->getDoctrine()->getRepository(Programme::class)->findOneBy(['enseignant' => $enseignant]);
        return $this->redirectToRoute('remplir_presences', ['id' => $seance->getId(), 'url'             => $programme->getFichierPdf()]);

    }

    /**
     * @Route("/presences/{id}", name="remplir_presences")
     */
    public function remplirPresence(Seance $seance){
        $classeMatiere = $seance->getMatiere()->getClasse();
        $enseignant = $this->getUser();
        $programme = $this->getDoctrine()->getRepository(Programme::class)->findOneBy(['enseignant' => $enseignant]);
        $eleves = $this->getDoctrine()->getRepository(Eleve::class)->findBy(['classe' => $classeMatiere ]);
        return $this->render('enseignant_d/presences.html.twig', ['eleves' => $eleves, 'seance' => $seance, 'url'             => $programme->getFichierPdf()]);
    }
    /**
     * @Route("/partie-matiere/{id}", name="parti_matiere")
     */
    public function partieMatiere(Matiere $matiere,Request $request){
        $ens= $this->getUser();
        $partieMatiere = $this->getDoctrine()->getRepository(PartieMatiere::class)->findOneBy(['enseignant' =>$ens, 'matiere'=>$matiere]);
        //dd($partieMatiere);
        $form = $this->createForm(PartieMatiereEnseignantType::class, $partieMatiere);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dd($form->getData());
            if($form->get('travauxAFaire')->getData()){
                $affectations = $ens->getAffectations();
                $classe = $matiere->getClasse();
                $eleves = $this->getDoctrine()->getRepository(Eleve::class)->findBy(['classe' => $classe]);
                foreach($eleves as $e){
                    $telParent = $e->getTelephoneParent();
                    $result = $this->orangeAuthentification();
                    $access_token = $result->access_token;
                    $resp   = $this->sendSMStoNumber("Votre enfant a un travaux a faire",$telParent, $access_token);
                }
               
            }
            $fichierPdf = $form->get('fichier')->getData();
            if($fichierPdf){
                $newFilename = 'fichier-'.uniqid().'.'.$fichierPdf->guessExtension();
            
                try {
                 $fichierPdf->move(
                     $this->getParameter('parties_m'),
                     $newFilename
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }
                 $partieMatiere->setFichier($newFilename);
            }else{
                $partieMatiere->setFichier(NULL);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($partieMatiere);
            $manager->flush();
            return $this->redirectToRoute('mes_matieres');
           
        }
        $enseignant = $this->getUser();
        $programme = $this->getDoctrine()->getRepository(Programme::class)->findOneBy(['enseignant' => $enseignant]);
        return $this->render('enseignant_d/partie-matiere.html.twig', [
            'form' => $form->createView(),
            'url'  => $programme->getFichierPdf()
        ]);
        /*$classeMatiere = $seance->getMatiere()->getClasse();
        $eleves = $this->getDoctrine()->getRepository(Eleve::class)->findBy(['classe' => $classeMatiere ]);
        return $this->render('enseignant_d/presences.html.twig', ['eleves' => $eleves, 'seance' => $seance]);*/
    }

     /// SMS 
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
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $http_status;
    }
    /// SMS END
    /**
     * @Route("/eleve/{matricule}/absent/{id}", name="absent")
     */
    public function absent($matricule, Seance $seance){
        
        $eleve = $this->getDoctrine()->getRepository(Eleve::class)->findOneBy(['matricule'=>$matricule]);
        $telParent = $eleve->getTelephoneParent();
        
        $result = $this->orangeAuthentification();
        $access_token = $result->access_token;
        $resp   = $this->sendSMStoNumber("Bonjour , votre enfant ne s'est pas présentée en cours ce matin",$telParent, $access_token);
        /*dd($resp);
        dd($eleve);
        dd("stop");*/
        $eleve->setSeance($seance);
        $this->getDoctrine()->getManager()->flush();
        if($resp === 201){
            return $this->redirectToRoute('remplir_presences',['id' => $seance->getId()]);
        }
        /**
         * ENVOI DES MESSAGES POUR CET ELEVE AU PARENT
         */
        return $this->redirectToRoute('remplir_presences', ['id' => $seance->getId()] );
    }

    /**
     * @Route("/detail-pm-matiere/{id}", name="detailler_pm")
     */
    public function detailler(Matiere $matiere){
        $connecte = $this->getUser();
        $pm = $this->getDoctrine()->getRepository(PartieMatiere::class)->findOneBy(['enseignant' => $connecte, 'matiere' => $matiere]);
        $enseignant = $this->getUser();
        $programme = $this->getDoctrine()->getRepository(Programme::class)->findOneBy(['enseignant' => $enseignant]);
        return $this->render('enseignant_d/detail-pm.html.twig', ['pm' => $pm,  'url'  => $programme->getFichierPdf()]);
    }

     /**
     * @Route("/modifier/pmmatiere/{id}", name="modifier_pm")
     */
    public function modifier(Matiere $matiere,Request $request){
    
        $connecte = $this->getUser();
        $pm = $this->getDoctrine()->getRepository(PartieMatiere::class)->findOneBy(['enseignant' => $connecte, 'matiere' => $matiere]);
        $oldfile = $pm->getFichier();
        //dd($oldfile);
        $form = $this->createForm(PartieMatiereEnseignantType::class, $pm);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->get('travauxAFaire')->getData()){
                $affectations = $connecte->getAffectations();
                $classe = $matiere->getClasse();
                $eleves = $this->getDoctrine()->getRepository(Eleve::class)->findBy(['classe' => $classe]);
                foreach($eleves as $e){
                    $telParent = $e->getTelephoneParent();
                    $result = $this->orangeAuthentification();
                    $access_token = $result->access_token;
                    $resp   = $this->sendSMStoNumber("Votre enfant a un travaux a faire",$telParent, $access_token);
                }
               
            }
            $fichierPdf = $form->get('fichier')->getData();
            if($fichierPdf){
                $newFilename = 'fichier-'.uniqid().'.'.$fichierPdf->guessExtension();
            
                try {
                 $fichierPdf->move(
                     $this->getParameter('parties_m'),
                     $newFilename
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }
                 $pm->setFichier($newFilename);
            }else{
                $pm->setFichier($oldfile);
            }
            $manager = $this->getDoctrine()->getManager();

            $manager->flush();
            return $this->redirectToRoute('mes_matieres');
        }
        $enseignant = $this->getUser();
        $programme = $this->getDoctrine()->getRepository(Programme::class)->findOneBy(['enseignant' => $enseignant]);
        return $this->render('enseignant_d/modifier-pm.html.twig', ['form' => $form->createView(),  'url'  => $programme->getFichierPdf()]);
    }

    /**
     * @Route("/telechager-programme", name="telecharger_programme")
     */
    /*public function telecharger(){

        $enseignant = $this->getUser();
        $programme  = $this->getDoctrine()->getRepository(Programme::class)->findOneBy(['enseignant' => $enseignant]);
        dd($programme); // 
    }*/
}
