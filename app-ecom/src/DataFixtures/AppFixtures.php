<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        // charger la bd 
        $c1 = new Category();
        $c1->setLabel("PIÈCES MOTEUR");
        $manager->persist($c1);
        $c2 = new Category();
        $c2->setLabel("DIRECTIONS SUSPENSION TRAIN");
        $manager->persist($c2);
        $c3 = new Category();
        $c3->setLabel("FREINAGE");
        $manager->persist($c3);
        $manager->flush();
    }
}
