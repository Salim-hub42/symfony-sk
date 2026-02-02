<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{


    protected UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setNom('khalfoun');
        $user->setPrenom('salim');
        $user->setEmail('salim.khalfoun@yahoo.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'salim123');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $admin = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin123');
        $admin->setNom('khal');
        $admin->setPrenom('sal');
        $admin->setEmail('salim.khalfoun@yah.fr');
        $admin->setPassword($hashedPassword);
        $admin->setRoles(['ROLE_ADMIN']);

        $employee = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($employee, 'employee123');
        $employee->setNom('khal');;
        $employee->setPrenom('zaki');
        $employee->setEmail('salim.khalfoun@gmail.fr');
        $employee->setPassword($hashedPassword);
        $employee->setRoles(['ROLE_EMPLOYEE']);




        $manager->persist($user);
        $manager->persist($admin);
        $manager->persist($employee);

        $manager->flush();
    }
}
