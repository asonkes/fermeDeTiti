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
        $categories = [
            'Boissons',
            'Fruits',
            'Légumes',
            'Boulangerie',
            'Crèmerie',
            'Bières'
        ];

        foreach ($categories as $name) {
            $category = new Categories;
            $category->setName($name)
                ->setSlug($this->slugger->slug($name)->lower());
            $manager->persist($category);

            $this->addReference('cat-' . $this->counter, $category);
            $this->counter++;
        }

        $manager->flush();
    }
}
