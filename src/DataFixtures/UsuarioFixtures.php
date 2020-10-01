<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsuarioFixtures extends Fixture
{
    public const ADMIN_USER_REF = 'user-admin';
    public const USER_REF = 'user';

    public function __construct(UserPasswordEncoderInterface $passEncoder)
    {
        $this->passEncoder = $passEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->makeUser($manager);
        $this->makeAdmin($manager);
    }

    public function makeAdmin(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passEncoder->encodePassword($user, '12345'));

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::ADMIN_USER_REF, $user);
    }

    public function makeUser(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@user.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passEncoder->encodePassword($user, '12345'));

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::USER_REF, $user);
    }
}
