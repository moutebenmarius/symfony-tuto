<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\CompleteCoachType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscrption", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
            $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $role = $form->get('role')->getData() ;

            if($role === "ROLE_COACH"){
                return $this->redirectToRoute('coach',['id'=>$user->getId()]);
            }else{
                $this->addFlash('success', 'Vous pouvez maintenant connecter');
                return $this->redirectToRoute('app_login');
            }
            //dd($form->get('role')->getData());
            // encode the plain password
            // do anything else you need here, like send an email
            
            //return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/completer-coach/{id}", name="coach")
     */
    public function coach(Utilisateur $user,Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $form = $this->createForm(CompleteCoachType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            
            $this->addFlash('success', 'Vous pouvez maintenant connecter');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/completer-coach.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    
}
