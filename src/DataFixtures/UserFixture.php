<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{


    private $encoder;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->encoder = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new User();
        $user->setFirstname('Quentin');
        $user->setName('Jeannet');
        $user->setEmail('admin@gmail.com');
        $user->setRoles(['ROLE_USER' , 'ROLE_ADMIN']);
        $password = $this->encoder->hashPassword($user, 'pass');
        $user->setPassword($password);
        $manager->persist($user);
        //
        $user = new User();
        $user->setFirstname('Thierry');
        $user->setName('Henry');
        $user->setEmail('user@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $password = $this->encoder->hashPassword($user, 'pass');
        $user->setPassword($password);
        $manager->persist($user);



        $manager->flush();
    }
}
