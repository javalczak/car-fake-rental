<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\FuelType;
use App\Entity\Vehicle;
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
        'hybryda',
        'paliwo rakietowe',
        'czary',
        'reaktor jądrowy'
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

    private array $city = [
        'Poznań',
        'Sochaczew',
        'Malbork',
        'Lublin',
        'Wałcz',
        'Świdwin',
        'Świdnik',
        'Mirosławiec'
    ];

    // dorzucamy parę fur
    private array $vehicle = [
        ['brandId' => '2', 'type' => 'SVX', 'vin' => '0987654321CCVFRT', 'plate' => 'PO-CSV55', 'description' => 'sportowe blachowkręty', 'maintenance' => false, 'fuelId' => 1],
        ['brandId' => '2', 'type' => 'Galant', 'vin' => '098FR5589SSWT', 'plate' => 'PO-4567DS', 'description' => 'trzykolorowy, ale 6 garów', 'maintenance' => false, 'fuelId' => 2],
        ['brandId' => '8', 'type' => 'Legendary', 'vin' => '09SDTYA76FRT', 'plate' => 'PO-AWARIA', 'description' => 'uszkodzony', 'maintenance' => true, 'fuelId' => 1],
        ['brandId' => '5', 'type' => 'SN24,', 'vin' => '...', 'plate' => 'PO-SUROWCE', 'description' => 'Ma kopyto', 'maintenance' => false, 'fuelId' => 6],
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

        // load brand names
        foreach ($this -> brandName as $item) {
            $newBrand = new Brand();
            $newBrand -> setName($item);

            $manager -> persist($newBrand);
        }

        $manager -> flush();

        // load cities
        foreach ($this -> city as $item) {
            $city = new City();
            $city -> setName($item);

            $manager -> persist($city);
        }

        $manager -> flush();

        // load some vehicles
        $brandRepo = $manager -> getRepository(Brand::class);
        $fuelRepo = $manager -> getRepository(FuelType::class);
        foreach ($this -> vehicle as $item) {

            $newVehicle = new Vehicle();
            $newVehicle -> setBrand($brandRepo -> find($item['brandId']));
            $newVehicle -> setFuel($fuelRepo -> find($item['fuelId']));
            $newVehicle -> setPlate($item['plate']);
            $newVehicle -> setVin($item['vin']);
            $newVehicle -> setType($item['type']);
            $newVehicle -> setDescription($item['description']);
            $newVehicle -> setMaintenance($item['maintenance']);
            $newVehicle -> setAddedAt(new DateTime('now'));

            $manager -> persist($newVehicle);

        }

        $manager -> flush();
    }
}
