<?php

namespace App\DataFixtures;

use App\Entity\Producer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProducerFixtures extends Fixture
{
    public $counter = 1;

    public function load(ObjectManager $manager): void
    {
        $producerData = [
            [
                'name' => 'Marc Janssens',
                'society' => 'Plantation du Beauregard',
                'description' => 'Entreprise respectant la récolte des fruits à maturité, 100% BIO',
                'zipcode' => '7830',
                'city' => 'Silly'
            ],
            [
                'name' => 'Stadnik Tiphaine',
                'society' => 'Ferme de Warelles',
                'description' => 'Entreprise respectant la récolte des fruits à maturité, 100% BIO',
                'zipcode' => '7850',
                'city' => 'Enghien'
            ]
        ];

        foreach ($producerData as $data) {

            $producer = new Producer();
            $producer->setName($data['name'])
                ->setSociety($data['society'])
                ->setDescription($data['description'])
                ->setZipcode($data['zipcode'])
                ->setCity($data['city']);

            $manager->persist($producer);

            $this->addReference('prod-' . $this->counter, $producer);
            $this->counter++;
        }

        $manager->flush();
    }
}
