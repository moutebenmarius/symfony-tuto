<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $ROLES   = ['ROLE_PHARMACIE','ROLE_MEDECIN','ROLE_LABORATOIRE','ROLE_IMAGERIE_MEDICALE', 'ROLE_PATIENT'];
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        // $product = new Product();
        // $manager->persist($product);
        // admin
        
         

        $admin = new Utilisateur();
        $admin->setRole("ROLE_ADMIN")
              ->setNomUtilisateur("admin")
              ->setPassword($this->encoder->encodePassword($admin, "12345678")) // mot de passe
              ->setNom($faker->firstName())
              ->setPrenom($faker->lastName())
              ->setAdresse($faker->address)
              ->setNumeroTelephone($faker->phoneNumber)
              ->setAdresseEmail($faker->freeEmail)
              ->setPhotoUrl("photo.png")
              ->setStatus("confirme");
        $manager->persist($admin);
        // creation d'autre utilisateur
        for($i = 0 ; $i < 50 ; $i++){
            $user = new Utilisateur();
            $user->setRole($this->ROLES[mt_rand(0,4)])
              ->setNomUtilisateur($faker->userName)
              ->setPassword($this->encoder->encodePassword($user, "12345678")) // mot de passe
              ->setNom($faker->firstName())
              ->setPrenom($faker->lastName())
              ->setAdresse($faker->address)
              ->setNumeroTelephone($faker->phoneNumber)
              ->setAdresseEmail($faker->freeEmail)
              ->setPhotoUrl("photo.png")
              ->setStatus("confirme");
            $manager->persist($user);
        }
        

        $manager->flush();
    }
}
