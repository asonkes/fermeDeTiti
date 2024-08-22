<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Products;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductsFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $productsData = [
            [
                'name' => 'jus de pommes',
                'image' => 'vue-face-jus-pomme-frais-pommes-fraiches-bureau-bois-marron-photo-cocktail-fruits-couleur-boisson_140725-92833.webp',
                'price' => '7.00',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-1',
                'alt' => 'image représentant un verre de jus de pommes avec des pommes à côté'
            ],
            [
                'name' => 'jus de poires',
                'image' => 'poires-dans-panier-seau-boisson-high-angle-view-tableau-blanc_176474-8761.webp',
                'price' => '7.50',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-1',
                'alt' => 'image représentant un verre de jus de poires avec des poires à côté'
            ],
            [
                'name' => "jus d'orange",
                'image' => 'couper-fruits-jus-orange-parapluie_23-2148145311.webp',
                'price' => '6.50',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-1',
                'alt' => "image représentant un verre d'orange avec des oranges à côté"
            ],
            [
                'name' => 'jus de cerises',
                'image' => 'cornels-du-jus-dans-verre-pot_114579-18055.webp',
                'price' => '6.50',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-1',
                'alt' => 'image représentant un verre de jus de cerises avec des cerises à côté dans un bol'
            ],
            [
                'name' => 'jus de prunes',
                'image' => 'prunes-jardin-dans-panier-fond-bleu-verre-jus-photo-haute-qualite_114579-53019.webp',
                'price' => '8.00',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-1',
                'alt' => 'image représentant un verre de jus de prunes avec des prunes à côté dans un panier'
            ],
            [
                'name' => 'jus de pêches',
                'image' => 'nectarines-au-jus-table-grungy-planche-decouper-vue-laterale_176474-8778.webp',
                'price' => '7.00',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-1',
                'alt' => 'image représentant un verre de jus de pêches avec des pêches à côté'
            ]
        ];

        foreach ($productsData as $data) {
            $product = new Products();
            $product->setName($data['name'])
                ->setImage($data['image'])
                ->setPrice($data['price'])
                ->setStock($data['stock'])
                ->setAlt($data['alt'])
                ->setSlug($this->slugger->slug($data['name'])->lower());

            // On va chercher une référence de catégorie
            $category = $this->getReference($data['category_reference']);
            $product->setCategories($category);

            // On va chercher une référence de produit
            $producer = $this->getReference($data['producer_reference']);
            $product->setProducer($producer);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
