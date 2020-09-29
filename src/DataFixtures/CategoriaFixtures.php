<?php

namespace App\DataFixtures;

use App\Entity\Categoria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriaFixtures extends Fixture
{

    public const CATEGORIA_INTERNET_REF = 'categoria-internet';
    
    public function load(ObjectManager $manager)
    {
        $categoria = new Categoria();
        $categoria->setNombre("Internet");
        $categoria->setColor("cyan");

        $manager->persist($categoria);
        $manager->flush();

        $this->addReference(self::CATEGORIA_INTERNET_REF, $categoria);
    }
}
