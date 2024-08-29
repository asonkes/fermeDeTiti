<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private SluggerInterface $slugger
    ) {}

    public function load(ObjectManager $manager): void
    {
        $admin = new Users;
        $admin->setEmail('infos_warelles@gmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(
                $this->passwordHasher->hashPassword($admin, 'admin')
            )
            ->setLastname('Stadnik')
            ->setFirstname('Tiphaine')
            ->setAddress('Rue noir mouchon 15')
            ->setZipcode('7850')
            ->setCity('Enghien');

        $manager->persist($admin);

        $faker = Factory::create('fr_BE');

        for ($i = 0; $i < 10; $i++) {
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();

            // J'enlève les caractères spéciaux des adresses mails
            $firstname = preg_replace('/[^a-z0-9!#$%&\'*+\=?^_`{|}~.]/i', '', $firstname);
            $lastname = preg_replace('/[^a-z0-9!#$%&\'*+\=?^_`{|}~.]/i', '', $lastname);

            //strtolower ==> garantit que les noms et prénoms dans l'adresse mail sont en miniscule
            $email = strtolower($firstname) . '.' . strtolower($lastname) . '@' . $faker->freeEmailDomain();

            $user = new Users();
            $user->setEmail($email)
                ->setPassword(
                    $this->passwordHasher->hashPassword($user, 'user')
                )
                ->setLastname($lastname)
                ->setFirstname($firstname)
                ->setAddress($faker->streetAddress())
                ->setZipcode($faker->postcode())
                ->setCity($faker->city());

            $manager->persist($user);
        };

        $manager->flush();
    }
}
