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
            // Base de données des boissons
            [
                'name' => 'jus de pommes',
                'image' => 'vue-face-jus-pomme-frais-pommes-fraiches-bureau-bois-marron-photo-cocktail-fruits-couleur-boisson_140725-92833.webp',
                'price' => '7.00',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-1',
                'alt' => 'image représentant un verre de jus de pommes avec des pommes à côté',
                'type' => 'litre'
            ],
            [
                'name' => 'jus de poires',
                'image' => 'poires-dans-panier-seau-boisson-high-angle-view-tableau-blanc_176474-8761.webp',
                'price' => '7.50',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un verre de jus de poires avec des poires à côté',
                'type' => 'litre'
            ],
            [
                'name' => "jus d'orange",
                'image' => 'couper-fruits-jus-orange-parapluie_23-2148145311.webp',
                'price' => '6.50',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-2',
                'alt' => "image représentant un verre d'orange avec des oranges à côté",
                'type' => 'litre'
            ],
            [
                'name' => 'jus de cerises',
                'image' => 'cornels-du-jus-dans-verre-pot_114579-18055.webp',
                'price' => '6.50',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-3',
                'alt' => 'image représentant un verre de jus de cerises avec des cerises à côté dans un bol',
                'type' => 'litre'
            ],
            [
                'name' => 'jus de prunes',
                'image' => 'prunes-jardin-dans-panier-fond-bleu-verre-jus-photo-haute-qualite_114579-53019.webp',
                'price' => '8.00',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-3',
                'alt' => 'image représentant un verre de jus de prunes avec des prunes à côté dans un panier',
                'type' => 'litre'
            ],
            [
                'name' => 'jus de pêches',
                'image' => 'nectarines-au-jus-table-grungy-planche-decouper-vue-laterale_176474-8778.webp',
                'price' => '7.00',
                'stock' => 10,
                'category_reference' => 'cat-1',
                'producer_reference' => 'prod-1',
                'alt' => 'image représentant un verre de jus de pêches avec des pêches à côté',
                'type' => 'litre'
            ],
            // Base de données des fruits
            [
                'name' => 'Pommes',
                'image' => 'vue-dessus-du-tas-pommes_23-2148795855.webp',
                'price' => '2.00',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-1',
                'alt' => 'image représentant un tas de pommes',
                'type' => 'kilo'
            ],
            [
                'name' => 'Poires',
                'image' => 'poires-jaunes-pois-rouges-dans-stock-epicerie_114579-9375.webp',
                'price' => '2.50',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de poires',
                'type' => 'kilo'
            ],
            [
                'name' => 'Cerises',
                'image' => 'vue-dessus-delicieux-arrangements-cerises_23-2149433492.webp',
                'price' => '2.00',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-3',
                'alt' => 'image représentant un tas de cerises',
                'type' => 'kilo'
            ],
            [
                'name' => 'Prunes',
                'image' => 'arriere-plan-prunes-biologiques-fraiches-dans-gouttes-eau-gros-plan-mise-au-point-selective-faible-p.webp',
                'price' => '3.50',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-3',
                'alt' => 'image représentant un tas de prunes',
                'type' => 'kilo'
            ],
            [
                'name' => 'Pêches',
                'image' => 'vue-face-peches-moelleuses-juteuses-bureau-bois-pulpe-ete-fruits_140725-22058.webp',
                'price' => '3.50',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-3',
                'alt' => 'image représentant un tas de pêches',
                'type' => 'kilo'
            ],
            [
                'name' => 'Fraises',
                'image' => 'fraises-coupees-deux-baies-entieres-baies-mures-vue-dessus-arriere-plan-fraises-mures-delicieux-dess.webp',
                'price' => '5.00',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de framboises',
                'type' => '500gr'
            ],
            [
                'name' => 'Framboises',
                'image' => 'gros-plan-framboises-fraiches-mignonnes-autre_181624-1270.webp',
                'price' => '5.50',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-4',
                'alt' => 'image représentant un tas de prunes',
                'type' => '300gr'
            ],
            [
                'name' => 'Myrtilles',
                'image' => 'fruits-sains-vendre-au-marche_23-2148263725.webp',
                'price' => '4.00',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-4',
                'alt' => 'image représentant un tas de myrtilles',
                'type' => '300gr'
            ],
            [
                'name' => 'Groseilles',
                'image' => 'arrangement-canneberges-vue-dessus_23-2148823574.webp',
                'price' => '4.00',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-4',
                'alt' => 'image représentant un tas de groseilles',
                'type' => '300gr'
            ],
            [
                'name' => 'Mûres',
                'image' => 'framboise-biologique-nutritive-marche_23-2148263724.webp',
                'price' => '4.0',
                'stock' => 100,
                'category_reference' => 'cat-2',
                'producer_reference' => 'prod-4',
                'alt' => 'image représentant un tas mûres',
                'type' => '300gr'
            ],
            // Base de données  des légumes
            [
                'name' => 'Pommes de terre',
                'image' => 'gros-plan-pommes-terre-plancher_23-2148540364.webp',
                'price' => '1.50',
                'stock' => 100,
                'category_reference' => 'cat-3',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de pommes de terre',
                'type' => 'kilo'
            ],
            [
                'name' => 'Tomates',
                'image' => 'vue-dessus-tomates-fraiches-mures-gouttes-eau-fond-noir_141793-3433.webp',
                'price' => '2.25',
                'stock' => 100,
                'category_reference' => 'cat-3',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de tomates',
                'type' => 'kilo'
            ],
            [
                'name' => 'Courgettes',
                'image' => 'arrangement-angle-eleve-courgettes_23-2148917717.webp',
                'price' => '2.50',
                'stock' => 100,
                'category_reference' => 'cat-3',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de tomates',
                'type' => 'kilo'
            ],
            [
                'name' => 'Aubergines',
                'image' => 'arrangement-aubergines-vue-dessus_23-2150317333.webp',
                'price' => '2.75',
                'stock' => 100,
                'category_reference' => 'cat-3',
                'producer_reference' => 'prod-2',
                'alt' => "image représentant un tas d'aubergines",
                'type' => 'kilo'
            ],
            [
                'name' => 'Poivrons',
                'image' => 'gros-plan-frais-poivrons-rouges_23-2147916242.webp',
                'price' => '3.20',
                'stock' => 100,
                'category_reference' => 'cat-3',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de poivrons',
                'type' => 'kilo'
            ],
            [
                'name' => 'Carottes',
                'image' => 'vue-dessus-carottes_23-2148622433.webp',
                'price' => '2.15',
                'stock' => 100,
                'category_reference' => 'cat-3',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de carottes',
                'type' => 'kilo'
            ],
            [
                'name' => 'Concombres',
                'image' => 'vue-dessus-du-concombre-horizontal-bois-brun_176474-1267.webp',
                'price' => '2.60',
                'stock' => 100,
                'category_reference' => 'cat-3',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de concombres',
                'type' => 'kilo'
            ],
            [
                'name' => 'Oignons',
                'image' => 'oignons-crus-coupes_144627-41767.webp',
                'price' => '1.50',
                'stock' => 100,
                'category_reference' => 'cat-3',
                'producer_reference' => 'prod-2',
                'alt' => "image représentant un tas d'oignons",
                'type' => 'kilo'
            ],
            [
                'name' => 'Petits pois',
                'image' => 'gros-plan-haricots-remplissant-cadre_125540-4398.webp',
                'price' => '1.45',
                'stock' => 100,
                'category_reference' => 'cat-3',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de petits pois',
                'type' => 'kilo'
            ],
            // Base de données pour la boulangerie
            [
                'name' => 'Pain Blanc rond',
                'image' => 'vue-delicieux-pain-cuit-au-four-dans-patisserie_23-2150379540.webp',
                'price' => '3.50',
                'stock' => 100,
                'category_reference' => 'cat-4',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de pains ronds',
                'type' => 'pièce'
            ],
            // Base de données pour la crèmerie
            [
                'name' => 'Lait',
                'image' => 'bouteille-lait-frais-verre_1150-17624.webp',
                'price' => '2.50',
                'stock' => 100,
                'category_reference' => 'cat-5',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant une bouteille de lait',
                'type' => 'litre'
            ],
            [
                'name' => 'Oeufs',
                'image' => 'nourriture-dietetique-keto-nature-morte_23-2149278983.webp',
                'price' => '2.50',
                'stock' => 100,
                'category_reference' => 'cat-5',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant une boite de 6 oeufs',
                'type' => '6 pièces'
            ],
            [
                'name' => 'Farine',
                'image' => 'sacs-ingredients-remplis-farine_23-2149482550.webp',
                'price' => '8.50',
                'stock' => 100,
                'category_reference' => 'cat-5',
                'producer_reference' => 'prod-2',
                'alt' => 'image représentant un tas de la farine',
                'type' => 'kilo'
            ],
            [
                'name' => 'Fromage Blanc',
                'image' => 'bouteille-lait-frais-verre_1150-17624.webp',
                'price' => '4.00',
                'stock' => 100,
                'category_reference' => 'cat-5',
                'producer_reference' => 'prod-5',
                'alt' => 'image représentant un sac de farine',
                'type' => 'pièce'
            ],
            [
                'name' => 'Fromage blanc aux fines herbes',
                'image' => 'composition-gros-plan-delicieux-plats-locaux_23-2148833818.webp',
                'price' => '4.50',
                'stock' => 100,
                'category_reference' => 'cat-5',
                'producer_reference' => 'prod-5',
                'alt' => 'image représentant un fromage blanc aux fines herbes',
                'type' => 'pièce'
            ],
            // Base de données des bières
            [
                'name' => 'Enghien Hiver',
                'image' => 'enghien-hiver.webp',
                'price' => '3.00',
                'stock' => 100,
                'category_reference' => 'cat-6',
                'producer_reference' => 'prod-6',
                'alt' => 'image représentant un bière et un verre à côté',
                'type' => '33cl'
            ],
            [
                'name' => 'Double Enghien Brune',
                'image' => 'double-enghien-bruin.webp',
                'price' => '2.88',
                'stock' => 100,
                'category_reference' => 'cat-6',
                'producer_reference' => 'prod-6',
                'alt' => 'image représentant un bière et un verre à côté',
                'type' => '33cl'
            ],
            [
                'name' => 'Enghien Brune',
                'image' => 'enghien-brune.webp',
                'price' => '2.50',
                'stock' => 100,
                'category_reference' => 'cat-6',
                'producer_reference' => 'prod-6',
                'alt' => 'image représentant un bière et un verre à côté',
                'type' => '33cl'
            ],
            [
                'name' => 'Enghien Blonde',
                'image' => 'enghien-blonde-600x600.webp',
                'price' => '3.00',
                'stock' => 100,
                'category_reference' => 'cat-6',
                'producer_reference' => 'prod-6',
                'alt' => 'image représentant un bière et un verre à côté',
                'type' => '33cl'
            ],
            [
                'name' => 'Enghien Noel',
                'image' => '17a1c7173961fac9410ad673fd801ee3.png',
                'price' => '3.00',
                'stock' => 100,
                'category_reference' => 'cat-6',
                'producer_reference' => 'prod-6',
                'alt' => 'image représentant un bière et un verre à côté',
                'type' => '33cl'
            ]
        ];

        foreach ($productsData as $data) {
            $product = new Products();
            $product->setName($data['name'])
                ->setImage($data['image'])
                ->setPrice($data['price'])
                ->setStock($data['stock'])
                ->setAlt($data['alt'])
                ->setType($data['type'])
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
