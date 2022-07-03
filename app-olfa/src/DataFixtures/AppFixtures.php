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
        $admin = new Utilisateur();
        $admin->setDateNaissance(new \DateTime());
        $admin->setCin(10001000);
        $admin->setPrenom("walid");
        $admin->setNom("mahjoub");
        $admin->setEmail("walid@ennour.tn");
        $admin->setEstArchive(false);
        $admin->setPassword($this->encoder->encodePassword($admin, "00000000"));
        $admin->setGenre("homme");
        $admin->setTelephone("75111111");
        $admin->setRole("ROLE_ADMIN");
        $admin->setDateNaissance(new \DateTime());
        $admin->setEstArchive(false);
        $admin->setPhotoUrl("standard.jpg");
        $manager->persist($admin);
        // $manager->persist($product);

        $manager->flush();
    }
}
