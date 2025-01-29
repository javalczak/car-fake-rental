<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;
use App\Entity\Admin;

class AppFixtures extends Fixture
{
    private $username = 'javalczak';
    private $pass = 'jadwigaPastuch';
    private $role = ['ROLE_ADMIN'];

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ){}

    public function load(ObjectManager $manager): void
    {
        $newUser = new Admin();
        $newUser -> setUsername($this -> username);
        $newUser -> setPassword($this -> hasher -> hashPassword($newUser, $this -> pass));
        $newUser -> setRoles($this -> role);
        $newUser -> setAddedAt(new DateTime('now'));

        $manager -> persist($newUser);
        $manager -> flush();
    }
}
