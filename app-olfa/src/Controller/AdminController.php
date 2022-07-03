<?php

namespace App\Controller;

use App\Entity\CahierTexte;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Evaluation;
use App\Entity\Niveau;
use App\Entity\ParametresCompteEleve;
use App\Entity\PartieMatiere;
use App\Entity\Section;
use App\Entity\Utilisateur;
use App\Form\ClasseType;
use App\Form\EleveType;
use App\Form\EnseignantType;
use App\Form\ModifierProviseurType;
use App\Form\NiveauType;
use App\Form\ProviseurType;
use App\Form\SectionType;
use App\Repository\CahierTexteRepository;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\EvaluationRepository;
use App\Repository\NiveauRepository;
use App\Repository\SectionRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/supprimer-proviseur/{id}", name="supprimer_proviseur")
     */
    public function supprimerProviseur(Utilisateur $proviseur){
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($proviseur);
        $manager->flush();
        $this->addFlash("info","Le proviseur a été supprimé avec success");
        return $this->redirectToRoute('app_admin');
    }

    /**
     * @Route("/modifier-proviseur/{id}", name="modifier_proviseur")
     */
    public function modifierProviseur(Utilisateur $proviseur, Request $request, UserPasswordEncoderInterface $encoder, UtilisateurRepository $utilisateurRepository){
        $form = $this->createForm(ModifierProviseurType::class, $proviseur);
        $form->handleRequest($request);
        $oldPassword = $proviseur->getPassword();
        if ($form->isSubmitted() && $form->isValid()) {

            $utilisateurRepository->add($proviseur);
            return $this->redirectToRoute('detail_proviseur', [], Response::HTTP_SEE_OTHER);
        }

        if($form->isSubmitted() && $form->isValid()){
            $password = $form->get('password')->getData();
            if(!($password === $oldPassword)){

                $hash     = $encoder->encodePassword($proviseur, $password);
                $proviseur->setPassword($hash);
            }else{
                $proviseur->setPassword($oldPassword);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($proviseur);
            $manager->flush();
            return $this->redirectToRoute('detail_proviseur');
        }
        return $this->render('admin/modifier-proviseur.html.twig', ['formulaire' => $form->createView()]);
       /* $manager = $this->getDoctrine()->getManager();
        $manager->remove($proviseur);
        $manager->flush();
        $this->addFlash("info","Le proviseur a été supprimé avec success");
        return $this->redirectToRoute('app_admin');*/
    }

    /**
     * @Route("/ajouter-proviseur", name="ajouter_proviseur")
     */
    public function ajouterProviseur(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $proviseurCount = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(['role'=>"ROLE_PROVISEUR"]);
        if(count($proviseurCount)>0){
            $this->addFlash("info","Vous avez déjà un proviseur");
            return $this->redirectToRoute('detail_proviseur');
        }
        $proviseur = new Utilisateur();
        $proviseur->setPhotoUrl("standard.jpg");
        $proviseur->setEstArchive(false);
        $proviseur->setRole("ROLE_PROVISEUR");
        $form = $this->createForm(ProviseurType::class, $proviseur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $plainPassword = $proviseur->getPassword();
           $hash          = $encoder->encodePassword($proviseur, $plainPassword);
           $proviseur->setPassword($hash);
           $manager = $this->getDoctrine()->getManager();
           $manager->persist($proviseur);
           $manager->flush();
           $this->addFlash('info', "l'ajout se fait avec succés");
           return $this->redirectToRoute('detail_proviseur');
        }
        return $this->render('admin/ajouter-proviseur.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }

    /**
     * @Route("/detail-proviseur", name="detail_proviseur")
     */
    public function detailProviseur(UtilisateurRepository $repo): Response
    {
        $proviseur = $repo->findOneBy(['role'=>"ROLE_PROVISEUR"]);
        if(!$proviseur){
           //return new Response("<script>alert('aucun proviseur trouvé')</script>");
           return $this->render('admin/detail-proviseur.html.twig', [
            'proviseur' => null,
        ]);
        }
        return $this->render('admin/detail-proviseur.html.twig', [
            'proviseur' => $proviseur,
        ]);
    }

    /**
     * @Route("/ajouter-enseignant", name="ajouter_enseignant")
     */
    public function ajouterEnsignant(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $enseignant = new Utilisateur();
        $enseignant->setEstArchive(false);
        $enseignant->setRole("ROLE_ENSEIGNANT");
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $diplomePdf = $form->get('diplome')->getData();
           $newFilename = 'diplome-'.uniqid().'.'.$diplomePdf->guessExtension();
           try {
            $diplomePdf->move(
                $this->getParameter('diplome_directory'),
                $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
           $enseignant->setDiplome($newFilename);
           $plainPassword = $enseignant->getPassword();
           $hash          = $encoder->encodePassword($enseignant, $plainPassword);
           $enseignant->setPassword($hash);
           $enseignant->setPhotoUrl("unknown.png");
           $manager = $this->getDoctrine()->getManager();
           $manager->persist($enseignant);
           $manager->flush();
           $this->addFlash('info', "l'ajout se fait avec succés");
           return $this->redirectToRoute('liste_enseignants');
        }
        return $this->render('admin/ajouter-enseignant.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }

    /**
     * @Route("/liste-ensignants", name="liste_enseignants")
     */
    public function listeEnseigants(UtilisateurRepository $repo): Response
    {
        $enseignants = $repo->findBy(['role'=>"ROLE_ENSEIGNANT",'estArchive' => false]);
        return $this->render('admin/liste-enseignant.html.twig', [
            'enseignants' => $enseignants,
            'retour'      => false
        ]);
    }

    /**
     * @Route("/ajouter-niveau", name="ajouter_niveau")
     */
    public function ajouterNiveau(Request $request): Response
    {
        $niveau = new Niveau();
        $niveau->setEstArchive(false);
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $verify = $this->getDoctrine()->getRepository(Niveau::class)->findOneBy(['niveau'=>$niveau->getNiveau()]);
            if($verify){
                $this->addFlash('info','niveau déjà existe');
                return $this->redirectToRoute('ajouter_niveau');
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($niveau);
            $manager->flush();
            $this->addFlash('info','le niveau a été ajouté');
            return $this->redirectToRoute('liste_niveaux');
        }
        return $this->render('admin/ajouter-niveau.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/liste-niveaux", name="liste_niveaux")
     */
    public function listeNiveaux(NiveauRepository $repo): Response
    {
        $niveaux = $repo->findBy(['estArchive' => false]);
        return $this->render('admin/liste-niveau.html.twig', [
            'niveaux' => $niveaux,
        ]);
    }

    /**
     * @Route("/ajouter-section", name="ajouter_section")
     */
    public function ajouterSection(Request $request): Response
    {
        $section = new Section();
        $section->setEstArchive(false);
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $verify = $this->getDoctrine()->getRepository(Section::class)->findOneBy(['section'=>$section->getSection()]);
            if($verify){
                $this->addFlash('info','section déjà existe');
                return $this->redirectToRoute('ajouter_section');
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($section);
            $manager->flush();
            $this->addFlash('info','la section a été ajoutée');
            return $this->redirectToRoute('liste_sections');
        }
        return $this->render('admin/ajouter-section.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/liste-sections", name="liste_sections")
     */
    public function listeSections(SectionRepository $section): Response
    {
        $sections = $section->findBy(['estArchive' => false]);
        return $this->render('admin/liste-sections.html.twig', [
            'sections' => $sections,
        ]);
    }

    /**
     * @Route("/ajouter-classe", name="ajouter_classe")
     */
    public function ajouterClasse(Request $request): Response
    {
        $classe = new Classe();
        $classe->setEstArchive(false);
        $form   = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $existe = $this->getDoctrine()->getRepository(Classe::class)->findOneBy(['section'=>$classe->getSection(), 'niveau'=>$classe->getNiveau()]);
            if($existe){
                $this->addFlash("warning","on peut pas repeter la meme classe");
                return $this->redirectToRoute('ajouter_classe');
            }
            if($classe->getSection() && !$classe->getNiveau()){
                $this->addFlash("warning","on peut enregistrer une classe sans niveau ");
                return $this->redirectToRoute('ajouter_classe');
                dd("impossible");
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($classe);
            $manager->flush();
            $this->addFlash('info','la classe a été ajoutée');
            return $this->redirectToRoute('liste_classes');
        }
        return $this->render('admin/ajouter-classe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/liste-classes", name="liste_classes")
     */
    public function listeClasses(ClasseRepository $classe): Response
    {
        $classes = $classe->findBy(['estArchive'=>false]);
        return $this->render('admin/liste-classes.twig', [
            'classes' => $classes,
        ]);
    }

    /**
     * @Route("/ajouter-eleve", name="ajouter_eleve")
     */
    public function ajouterEleve(Request $request): Response
    {
        $eleve = new Eleve();
        $eleve->setEstArchive(false);
        $form   = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$eleve->setPhoto("unknown.png");

            // récuperer matricule
            $matricule = $form->get('matricule')->getData();
            // verifier si la matricule est deja existe
            $eleveExiste = $this->getDoctrine()
            ->getRepository(Eleve::class)->findOneBy(['matricule' => $matricule]);
            if($eleveExiste){
               $this->addFlash('warning', "L'élève est déjà inscrit");
                return $this->redirectToRoute('liste_eleves');
                //return $form->get('matricule')->addError(new FormError("L'élève est déjà inscrit"));
            }
            //dd($eleveExiste);
           $photo = $form->get('photo')->getData();
           $newFilename = 'photo-'.uniqid().'.'.$photo->guessExtension();
           
           try {
            $photo->move(
                $this->getParameter('eleves_directory'),
                $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $eleve->setPhoto($newFilename);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($eleve);
            $manager->flush();
            $this->addFlash('info',"l' éleve a été ajouté");
            return $this->redirectToRoute('liste_eleves');
        }
        return $this->render('admin/ajouter-eleve.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/liste-eleves", name="liste_eleves")
     */
    public function listeEleves(EleveRepository $repo): Response
    {
        $eleves = $repo->findBy(['estArchive' => false]);
        return $this->render('admin/liste-eleves.html.twig', [
            'eleves' => $eleves,
            'retour' => false
        ]);
    }

     /**
     * @Route("/liste-eleves-classe/{id}", name="liste_eleves_classe")
     */
    public function listeEleveClasse(Classe $classe): Response
    {
        $eleves = $classe->getEleves();
        
        return $this->render('admin/liste-eleves-classe.html.twig', [
            'eleves' => $eleves,
            'classe' => $classe
        ]);
    }

    

    /**
     * @Route("/liste-cahiers", name="liste_cahiers")
     */
    public function listeCahiers(CahierTexteRepository $cahierTexteRepository): Response
    {

        return $this->render('admin/liste-cahiers.html.twig', [
            'cahier_textes' => $cahierTexteRepository->findBy(['estArchive'=>false]),
        ]);
    }


    /**
     * @Route("/eleve/chercher", name="chercher_eleve")
     */
    public function chercherEleve(Request $request, EleveRepository $eleveRepository): Response
    {
        $q  = $request->get('q');
        $eleves = $eleveRepository->getEleveByKeyword($q);
        $request->getQueryString();
        return $this->render('admin/liste-eleves.html.twig', [
            'eleves' => $eleves,
            'retour' => true
        ]);
        /*return $this->render('admin/liste-cahiers.html.twig', [
            'controller_name' => 'AdminController',
        ]);*/
    }

    /**
     * @Route("/enseignant/chercher", name="chercher_enseignant")
     */
    public function chercherEnseignant(Request $request, UtilisateurRepository $enseignantRepo): Response
    {
        $q  = $request->get('q');
        $enseignants = $enseignantRepo->getEnseigantByKeyword($q);
        return $this->render('admin/liste-enseignant.html.twig', [
            'enseignants' => $enseignants,
            'retour'      => true
        ]);
        /*return $this->render('admin/liste-cahiers.html.twig', [
            'controller_name' => 'AdminController',
        ]);*/
    }


    /**
     * @Route("/generer-compte", name="generer_compte")
     */
    public function generCompte(Request $request, UserPasswordEncoderInterface $encoder){
        date_default_timezone_set("Africa/Tunis");
        $form = $this->createFormBuilder()->add('de', DateType::class, [
            'label' => 'à partir de ',
            'attr' => [
                'class' => 'form-control'
            ],
            'widget' => "single_text"
        ])->add('a', DateType::class,[
            'label' => "jusqu'à",
            'attr' => [
                'class' => 'form-control'
            ],
            'widget' => 'single_text'
        ])->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $de = $form->get('de')->getData();
            $a  = $form->get('a')->getData();
            $manager = $this->getDoctrine()->getManager();
            $para = new ParametresCompteEleve();
            $para->setA($a);
            $para->setDe($de);
            $manager->persist($para);
            
            $eleves = $this->getDoctrine()->getRepository(Eleve::class)->findAll();
            $ancienElevesComptes = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(['role'=> 'ROLE_ELEVE']);
            foreach ($ancienElevesComptes as $ancien) {
               $manager->remove($ancien);
               $manager->flush();
            }
            foreach($eleves as $eleve){
                $matricule = $eleve->getMatricule();
                $cinParent = $eleve->getCinParent();
                $utilisateur = new Utilisateur();
                $utilisateur->setRole('ROLE_ELEVE');
                $hash        = $encoder->encodePassword($utilisateur,$matricule);
                $utilisateur->setPassword($hash);
                $utilisateur->setCin($cinParent);
                $utilisateur->setNom($eleve->getNom());
                $utilisateur->setPrenom($eleve->getPrenom());
                $utilisateur->setGenre($eleve->getGenre());
                $utilisateur->setTelephone($eleve->getTelephoneParent());
                $utilisateur->setEmail("eleve@ennour.com");
                $utilisateur->setDateNaissance($eleve->getDateNaissance());
                $utilisateur->setEstArchive(false);
                $manager->persist($utilisateur);
            }
            $manager->flush();
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/generer-compte.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/cahier/consulter/{id}", name="consulter")
     */
    public function consulterCahier(CahierTexte $cahier): Response
    {
        $partieMatieres = $this->getDoctrine()->getRepository(PartieMatiere::class)->findBy(['cahierTexte' => $cahier]);
        return $this->render('admin/partie-matiere.html.twig', [
            'parties' => $partieMatieres
        ]);
    }


     /**
     * @Route("/visualiser/signer/{id}", name="signer")
     */
    public function signer(PartieMatiere $partieMatiere): Response
    {
        return $this->render('admin/visualiser-pm.html.twig',['pm' => $partieMatiere]);
    
    }

     /**
     * @Route("/valider/{id}", name="valider")
     */
    public function valider(PartieMatiere $partieMatiere): Response
    {
        $partieMatiere->setEstVuParDirecteur(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        return $this->redirectToRoute('liste_cahiers');
    }

      /**
     * @Route("/statistques", name="statistiques")
     */
    public function statistiques(): Response
    {
        //$evalutions = $this->getDoctrine()->getRepository(Evaluation::class)->findAll();
        $enseignants = $this->getDoctrine()->getRepository(Utilisateur::class)->findBy(['role' => 'ROLE_ENSEIGNANT']);
        
        return $this->render('admin/statistiques.html.twig', ['enseignants' => $enseignants]);
    }

     /**
     * @Route("/voir-statistique/{id}", name="voir_statistique")
     */
    public function voir_statistique(Utilisateur $enseignant, EvaluationRepository $repo): Response
    {
       
        $TresSatisfait = $repo->getEvalutionOfEnsInValeur("Trés Satisfait", $enseignant);
        $Statisfait = $repo->getEvalutionOfEnsInValeur("Satisfait", $enseignant);
        $Neutre = $repo->getEvalutionOfEnsInValeur("Neutre", $enseignant);
        $passatisfait = $repo->getEvalutionOfEnsInValeur("Pas Satisfait", $enseignant);
        $pasdetoussatisfait = $repo->getEvalutionOfEnsInValeur("Pas de tous Satisfait ", $enseignant);
        $stats = [
            count($TresSatisfait),
            count($Statisfait),
            count($Neutre),
            count($passatisfait),
            count($pasdetoussatisfait)           
        ];
        return $this->render('admin/voir-statistiques.html.twig', ['stats' => $stats]);
    }

}
