<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    public $counter = 1;

    public function __construct(private SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            [
                'name' => 'Boissons',
                'image' => 'smoothies-aux-fruits-frais-fond-bois_23-2148227524.webp',
                'alt' => 'image représentant différentes bouteilles de jus de fruits',
            ],
            [
                'name' => 'Fruits',
                'image' => 'arrangement-fruits-savoureux-sous-angle-eleve_23-2148545917.webp',
                'alt' => 'image représentant différents fruits dont annanas, kiwis et oranges'
            ],
            [
                'name' => 'Légumes',
                'image' => 'legume-vue-face_140725-103355-1.webp',
                'alt' => 'image représentant différents légumes dont des tomates, poivrons et aubergines'
            ],
            [
                'name' => 'Boulangerie',
                'image' => 'boulangerie_23-2148011544.webp',
                'alt' => 'image représentant un pain, des baguettes et des tartines coupées'
            ],
            [
                'name' => 'Crèmerie',
                'image' => 'delicieux-morceaux-fromage_144627-43352.webp',
                'alt' => 'image représentant différents morceaux de fromage différents sur une planche en bois'
            ],
            [
                'name' => 'Bières',
                'image' => 'biere-aux-epis-ble-chips-dans-verre-table-bois-high-angle-view_176474-5888.webp',
                'alt' => 'image représentant une bière dans un verre posé sur une table'
            ]
        ];

        foreach ($categoriesData as $data) {
            $category = new Categories;
            $category->setName($data['name'])
                ->setImage($data['image'])
                ->setAlt($data['alt'])
                ->setSlug($this->slugger->slug($data['name'])->lower());
            $manager->persist($category);

            $this->addReference('cat-' . $this->counter, $category);
            $this->counter++;
        }

        $manager->flush();
    }
}
