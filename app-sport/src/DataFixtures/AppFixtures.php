<?php

namespace App\DataFixtures;

use App\Entity\TypeSport;
use App\Entity\Utilisateur;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $admin = new Utilisateur();
        $admin->setRole("ROLE_ADMIN");
        $admin->setNom("Moncer");
        $admin->setPrenom("Amal");
        $admin->setAdresse("cite el amel 4 gabes");
        $admin->setEmail("admin@app.tn");
        $admin->setDateNaissance(new DateTime());
        $admin->setGenre("homme");
        $hash = $this->encoder->encodePassword($admin, "00000000");
        $admin->setPassword($hash);
        $manager->persist($admin);
        // charger types du sport
        $types = [
            "Body Combat",
            "zumba-step",
            "handball",
            "crossfit",
            "karate",
            "kick Boxing"
        ];
        foreach($types as $type){
            $typeSport = new TypeSport();
            $typeSport->setLibelle($type);
            $manager->persist($typeSport);
        }
        $manager->flush();
    }
}
