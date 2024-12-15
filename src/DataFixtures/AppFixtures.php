<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Repair;
use DateTimeImmutable;
use App\Entity\History;
use App\Entity\Category;
use App\Entity\Location;
use App\Entity\Movement;
use App\Entity\Condition;
use App\Entity\Equipment;
use App\Entity\Maintenance;
use App\Entity\MovementType;
use App\Entity\MaintenanceType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


        // Données fixes pour les types de mouvements
        $movementTypes = [
            'loan', 'return', 'repair', 'maintenance' // prêt, retour, réparation, entretien
        ];

        foreach ($movementTypes as $i => $type) {
            $movementType = new MovementType();
            $movementType->setName($type);
            $this->addReference('movementType_' . $i, $movementType);
            $manager->persist($movementType);
        }

        // Données fixes pour les catégories d'équipements
        $categories = [
            'Saddles', 'Bridles', 'Bits', 'Pads', 'Reins' // Selles, Bridons, Mors, Tapis de selle, Rênes
        ];
        
        foreach ($categories as $i => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('category_' . $i, $category);
            $manager->persist($category);
        }

        // Liste des types de maintenance
        $maintenanceTypes = [
            'Routine Check', 'Cleaning', 'Leather Conditioning', 'Adjustments', 'Repair', 'Replacement', 'Safety Check', 'Inspection', 'Fitting Adjustment'
        ];


        foreach ($maintenanceTypes as $i => $type) {
            $maintenanceType = new MaintenanceType();
            $maintenanceType->setName($type);
            $this->addReference('maintenance_type_' . $i, $maintenanceType);
            $manager->persist($maintenanceType);
        }


        // Générer 10 emplacements aléatoires
        for ($i = 0; $i < 50; $i++) {
            $location = new Location();
            $location->setAisle('Aisle ' . $faker->randomElement(['A', 'B', 'C', 'D', 'E']));
            $location->setShelf('Shelf ' . $faker->numberBetween(1, 20));
            $this->addReference('location_' . $i, $location);
            $manager->persist($location);
        }

        // Données fixes pour l'état des équipements
        $conditions = ['New', 'Good', 'Worn', 'Under Repair', 'Out of Service'];

        foreach ($conditions as $i => $conditionName) {
            $condition = new Condition();
            $condition->setName($conditionName);
            $this->addReference('condition_' . $i , $condition);
            $manager->persist($condition);
        }

        // Utilisation des références des catégories, conditions et emplacements

        for ($i = 0; $i < 20; $i++) { // Générer 20 équipements
            $equipment = new Equipment();
            $equipment->setName($faker->word())
                    ->setDescription($faker->sentence())
                    ->setCategory($this->getReference('category_' . $faker->numberBetween(0, count($categories) - 1))) // Récupérer la référence de la catégorie
                    ->setLocation($this->getReference('location_' . $faker->numberBetween(0, 49))) // Récupérer la référence de l'emplacement
                    ->setStat($this->getReference('condition_' . $faker->numberBetween(0, count($conditions) - 1))) // Récupérer la référence de la condition
                    ->setCreatedAt(new DateTimeImmutable($faker->dateTimeThisYear()->format('Y-m-d H:i:s'))) // Convertir en DateTimeImmutable
                    ->setPrice($faker->randomFloat(2, 10, 500))
                    ->setLastMovement(new DateTimeImmutable($faker->dateTimeThisYear()->format('Y-m-d H:i:s'))); // Convertir en DateTimeImmutable
            $this->addReference('equipment_' . $i, $equipment);
            $manager->persist($equipment);
        }

        // Création des utilisateurs
        $users = []; // Tableau pour stocker les utilisateurs
        for ($u = 0; $u < 50; $u++) { 
            $user = new User();

            // Définir le mot de passe de l'admin
            if ($u === 3) {
                $adminPassword = 'password';
                $user->setRoles(["ROLE_ADMIN"])
                    ->setEmail("admin@test.test");
            } else {
                $user->setEmail($faker->freeEmail());
                $adminPassword = 'password';
            }

            // Hasher le mot de passe de l'utilisateur
            $hash = $this->hasher->hashPassword($user, $adminPassword);
            
            // Appliquer le mot de passe haché à l'utilisateur
            $user->setPassword($hash);
            $users[] = $user; // Ajoute l'utilisateur au tableau
            $this->addReference('user_' . $u, $user);
            
            $manager->persist($user);
        }      

        // Mouvements
        for ($i = 0; $i < 50; $i++) { // Générer 50 mouvements
            $movement = new Movement();
            $movement->setEquipment($this->getReference('equipment_' . $faker->numberBetween(0, 19))) // Récupérer une référence d'équipement
                    ->setMovementType($this->getReference('movementType_' . $faker->numberBetween(0, count($movementTypes) - 1))) // Récupérer un type de mouvement
                    ->setMovementDate(new \DateTimeImmutable($faker->dateTimeThisYear()->format('Y-m-d H:i:s'))) // Date du mouvement
                    ->setUser($this->getReference('user_' . $faker->numberBetween(0, 49))) // Récupérer une référence d'utilisateur
                    ->setComment($faker->sentence()); // Commentaire sur le mouvement
            $this->addReference('movement_' . $i, $movement);
            $manager->persist($movement);
        }

        // Réparations
        for ($i = 0; $i < 30; $i++) { // Générer 30 réparations
            // Récupérer uniquement les équipements qui sont en état "Under Repair"
            $underRepairEquipments = [];
            
            // Parcourir tous les équipements pour trouver ceux qui sont "Under Repair"
            for ($j = 0; $j < 20; $j++) {
                $equipment = $this->getReference('equipment_' . $j);
                if ($equipment->getStat()->getName() === 'Under Repair') {
                    $underRepairEquipments[] = $equipment; 
                }
            }

            if (!empty($underRepairEquipments)) {
                $repair = new Repair();
                $repair->setEquipment($faker->randomElement($underRepairEquipments)) // Récupérer un équipement en état "Under Repair"
                    ->setReportDate(new \DateTimeImmutable($faker->dateTimeThisYear()->format('Y-m-d H:i:s'))) // Date du rapport
                    ->setStartDate(new \DateTimeImmutable($faker->dateTimeBetween('-1 months', 'now')->format('Y-m-d H:i:s'))) // Date de début
                    ->setEndDate(new \DateTimeImmutable($faker->dateTimeBetween('now', '+1 months')->format('Y-m-d H:i:s'))) // Date de fin
                    ->setDescription($faker->sentence()); // Description de la réparation

                $manager->persist($repair);
            }
        }

        // Historique
        for ($i = 0; $i < 50; $i++) { // Générer 50 enregistrements d'historique
            $movementReference = $this->getReference('movement_' . $faker->numberBetween(0, 29)); // Récupérer une référence de mouvement
            $history = new History();
            $history->setEquipment($this->getReference('equipment_' . $faker->numberBetween(0, 19))) // Récupérer une référence d'équipement
                    ->setMovement($movementReference) // Récupérer la référence de mouvement
                    ->setUser($this->getReference('user_3')) // Assigner l'utilisateur Admin
                    ->setEventDate(new \DateTimeImmutable($faker->dateTimeThisYear()->format('Y-m-d H:i:s'))) // Date de l'événement
                    ->setComment($faker->sentence()); // Commentaire

            // Vérifier si le mouvement est de type 'repair' ou 'maintenance'
            if (in_array($movementReference->getMovementType()->getName(), ['repair', 'maintenance'])) {
                // Forcer l'utilisateur Admin pour les mouvements de type repair ou maintenance
                $history->setUser($this->getReference('user_3'));
            } else {
                // Assigner un utilisateur aléatoire pour les autres mouvements
                $history->setUser($users[$faker->numberBetween(0, count($users) - 1)]);
            }

            $manager->persist($history);
        }


        // Maintenances
        for ($i = 0; $i < 30; $i++) { // Générer 30 maintenances
            $maintenance = new Maintenance();
            $maintenance->setEquipment($this->getReference('equipment_' . $faker->numberBetween(0, 19))) // Récupérer une référence d'équipement
                        ->setMaintenanceDate($faker->dateTimeThisYear())
                        ->setMaintenanceType($this->getReference('maintenance_type_' . $faker->numberBetween(0, count($maintenanceTypes) - 1)))
                        ->setCost($faker->randomFloat(2, 50, 300))
                        ->setDescription($faker->sentence(10));

            $manager->persist($maintenance);
        }

        $manager->flush();
    }
}
