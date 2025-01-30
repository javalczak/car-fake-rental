<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\FuelType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;
use App\Entity\Admin;

class AppFixtures extends Fixture
{
    private string $username = 'javalczak';
    private string $pass = 'jadwigaPastuch';
    private array $role = ['ROLE_ADMIN'];

    private array $fuelType = [
        'benzyna',
        'LPG',
        'wodór',
        'diesel',
        'energia elektryczna',
        'elerium-115',
        'siła mięśni',
        'paliwo rakietowe',
        'czary'
    ];

    private array $brandName = [
        'Mitsubishi',
        'Subaru',
        'Ford',
        'Opel',
        'Tesla',
        'Starship',
        'Miotła',
        'DMC DeLorean',
        'Trampki'
    ];

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ){}

    public function load(ObjectManager $manager): void
    {
        // load primary user
        $newUser = new Admin();
        $newUser -> setUsername($this -> username);
        $newUser -> setPassword($this -> hasher -> hashPassword($newUser, $this -> pass));
        $newUser -> setRoles($this -> role);
        $newUser -> setAddedAt(new DateTime('now'));

        $manager -> persist($newUser);
        $manager -> flush();

        // load fuel type
        foreach ($this -> fuelType as $item) {
            $newFuleType = new FuelType();
            $newFuleType -> setFuel($item);
            $newFuleType -> setAddedAt(new DateTime('now'));

            $manager -> persist($newFuleType);
        }

        $manager -> flush();

        foreach ($this -> brandName as $item) {
            $newBrand = new Brand();
            $newBrand -> setName($item);

            $manager -> persist($newBrand);
        }

        $manager -> flush();
    }
}
