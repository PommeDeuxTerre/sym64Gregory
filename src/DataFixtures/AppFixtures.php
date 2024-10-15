<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->hasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // users
        $user = new User();
        $user->setUsername("admin");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($this->hasher->hashPassword($user, 'admin'));
        $users[] = $user;

        $manager->persist($user);

        for($i=1;$i<=10;$i++){
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, 'user'.$i));
            $users[] = $user;

            $manager->persist($user);
        }

        $manager->flush();
    }
}
