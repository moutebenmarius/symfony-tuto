<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Formation;
use App\Entity\Solution;
use App\Entity\Utilisateur;
use App\Form\CommandeType;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\FormationRepository;
use App\Repository\SolutionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="app_site")
     */
    public function index(): Response
    {
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        $solutions  = $this->getDoctrine()->getRepository(Solution::class)->findBy(['estArchive'=>false]);
        return $this->render('site/index.html.twig', [
            'formations' => $formations,
            'solutions'  => $solutions
        ]);
    }

    /**
     * @Route("/formations", name="formations")
     */
    public function formations(FormationRepository $repo): Response
    {
        return $this->render('site/formations.html.twig', [
            'formations' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/solutions", name="solutions")
     */
    public function solutions(SolutionRepository $repo): Response
    {
        
        return $this->render('site/solutions.html.twig', [
            'solutions' => $repo->findAll(),
        ]);
    }


   


    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('site/contact.html.twig', [
            'contact' => 'SiteController',
        ]);
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentation(): Response
    {
        return $this->render('site/presentation.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * @Route("/commander/{id}", name="commander")
     */
    public function commander(Request $request, Solution $solution): Response
    {
        $commande = new Commande();
        $commande->setSolution($solution);
        $commande->setDateCommande(new \DateTime());
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $clientData = $form->get('client')->getData();
            $client = new Client();
            $client->setNom($clientData->getNom());
            $client->setPrenom($clientData->getPrenom());
            $client->setAdresse($clientData->getAdresse());
            $client->setTelephone($clientData->getTelephone());
            $client->setTypeClient($clientData->getTypeClient());
            $client->addCommande($commande);
            $prixTotal = $solution->getPrix() * $commande->getQte();

            $manager = $this->getDoctrine()->getManager();
            $commande->setPrixTotal($prixTotal);
            $manager->persist($client);
            //$commande->setClient($client);
            $manager->persist($commande);
            $manager->flush();
            return $this->redirectToRoute('app_site');
        }
        return $this->render('site/commander.html.twig', [
            'solution' => $solution,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mot_de_passe_oublie", name="mot_de_passe_oublie")
     *
     */
    public function motdePasseOublie(Request $request, MailerInterface $mailer){
        $form = $this->createFormBuilder(null)->add('email',TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $email = $form->get('email')->getData();
            $existe = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);
            if(!$existe){
                $this->addFlash('info', "L'email n'existe pas");
                return $this->render('site/mot-passe-oublie.html.twig');
            }else{
                $email = (new Email())->from('centrethinktech@gmail.com')->to('jihed.gouay@gmail.com')->subject('Votre Mot de Passe')->text('text')->html('<h3>Votre Nouveau Mot de passe</h3>');
                $mailer->send($email);
            }
        }
        return $this->render('site/mot-passe-oublie.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
