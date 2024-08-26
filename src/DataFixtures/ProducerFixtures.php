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
                'description' => "Agricultrice raisonnée et aimant son travail, système de biométhanisation, accueil d'école maternelles et primaire, stages à la ferme durant l'été.",
                'zipcode' => '7850',
                'city' => 'Enghien'
            ],
            [
                'name' => 'Mathieu Guillaume',
                'society' => 'Les Vergers de Barry',
                'description' => 'Producteurs de petits fruits dans le respect de la terre et de la culture BIO.',
                'zipcode' => '7534',
                'city' => 'Tournai'
            ],
            [
                'name' => 'Edouard Menet',
                'society' => 'Les Petits Fruits.be',
                'description' => 'Producteurs de fruits BIO au coeur de la Wallonie Picarde !',
                'zipcode' => '7870',
                'city' => 'Montignies-lez-lens'
            ],
            [
                'name' => 'Louis Oostendorp',
                'society' => 'Les fromages de Thoricourt',
                'description' => 'Exploitation familiale proposant essentiellement du lait de vache en délicieux fromages.',
                'zipcode' => '7830',
                'city' => 'Silly'
            ],
            [
                'name' => 'Bertrand Van der Haegen',
                'society' => 'La brasserie de Silly',
                'description' => 'Le brassage de la bière est un savoir-faire qui se transmet depuis 6 générations à la brasserie de Silly, et ce depuis 1850.',
                'zipcode' => '7830',
                'city' => 'Silly'
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
