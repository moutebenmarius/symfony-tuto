<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
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
        // charger un admin
        $admin = new Utilisateur();
        $admin->setRole("ROLE_ADMIN");
        $admin->setEmail("admin@khadamni.tn");
        $admin->setPassword($this->encoder->encodePassword($admin, "00000000"));
        $admin->setNom("ghofrane");
        $admin->setPrenom("roua");
        $admin->setAdresse("iset gabes");
        $admin->setNumeroTelephone("75333000");
        $admin->setStatus("active");
        $manager->persist($admin);
        $manager->flush();
    }
}
