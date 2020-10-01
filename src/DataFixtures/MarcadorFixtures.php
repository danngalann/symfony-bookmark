<?php

namespace App\DataFixtures;

use App\Entity\Marcador;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MarcadorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $marcador = new marcador();
            $marcador->setNombre('Google ' . $i);
            $marcador->setUrl('http://google.com');
            $marcador->setCategoria(
                $this->getReference(CategoriaFixtures::CATEGORIA_INTERNET_REF)
            );
            $marcador->setUser($this->getReference(UsuarioFixtures::USER_REF));

            $manager->persist($marcador);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [UsuarioFixtures::class, CategoriaFixtures::class];
    }
}
