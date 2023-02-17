<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Good;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $headphones = (new Good())
            ->setName('Sony WH-1000XM5')
            ->setPrice(100.0);

        $phoneCase = (new Good())
            ->setName('Samsung Galaxy S22 Case Magenta Confetti')
            ->setPrice(20.0);

        $de = (new Country())
            ->setName('Germany')
            ->setVat(19)
            ->setTaxIdFormat('DEXXXXXXXXX');

        $it = (new Country())
            ->setName('Italy')
            ->setVat(22)
            ->setTaxIdFormat('ITXXXXXXXXXXX');

        $gr = (new Country())
            ->setName('Greece')
            ->setVat(24)
            ->setTaxIdFormat('GRXXXXXXXXX');

        $manager->persist($headphones);
        $manager->persist($phoneCase);
        $manager->persist($de);
        $manager->persist($it);
        $manager->persist($gr);

        $manager->flush();
    }
}
