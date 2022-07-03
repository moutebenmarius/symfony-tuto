<?php

namespace App\DataFixtures;

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
        $admin->setDateNaissance(new DateTime());
        $admin->setEmail("admin@thinktech.com");
        $admin->setNom("ben jemaa");
        $admin->setPrenom("Houssem eddine");
        $admin->setPassword($this->encoder->encodePassword($admin, "12345678"));
        $admin->setAdresse("rue khairededdine gabes");
        $admin->setTelephone("75390666");
        $admin->setVille("Gabés");
        $admin->setRole("ROLE_ADMIN");
        $admin->setCin(mt_rand(1000000,99999999));
        $manager->persist($admin);

        $manager->flush();
    }
}
