<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Products>
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    // Permet de débuter la pagination
    public function findProductsPaginated(int $page, string $slug, int $limit = 6): array
    {
        //pagination commence
        // abs (valeur absolue) ==> permet de toujours avoir une limite même si on met une valeur négative
        $limit = abs($limit);

        $result = [];

        $query = $this->getEntityManager()->createQueryBuilder()
            // c= catégories et 'p' = products
            ->select('c', 'p')
            // On va cherches les products
            ->from('App\Entity\Products', 'p')
            // On va chercher la catégorie correspondante aux produits (p.categories)
            ->join('p.categories', 'c')
            ->where("c.slug = '$slug'")
            ->setMaxResults($limit)
            // Permet d'aller chercher juste les produits qui correspondent à ma page et à ma limit
            ->setFirstResult(($page * $limit) - $limit);

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        // On vérifie que l'on a des données
        if (empty($data)) {
            return $result;
        }

        // On calcule le nombre de pages
        $pages = ceil($paginator->count() / $limit);

        // On remplit le tableau
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;

        return $result;
    }
}
