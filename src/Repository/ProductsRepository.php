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
    public function findProductsPaginated(int $page, string $slug, int $limit): array
    {
        //pagination commence
        // abs (valeur absolue) ==> permet de toujours avoir une valeur positive même si une valeur négative est accidentellement insérée.
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

        // $paginator encapsule les informations de $query dans un objet Paginator pour l'éxécuté de manière optimlmisée
        $paginator = new Paginator($query);
        // Là, on exécute la requête et récupère les objets pour la page courante
        $data = $paginator->getQuery()->getResult();

        // On vérifie que l'on a des données
        if (empty($data)) {
            // Vous pouvez définir un message à passer à votre template
            $this->addFlash('warning', "Aucun produit n'a été trouvé pour cette catégorie");

            return $result;
        }

        // On calcule le nombre de pages 
        // Pour cela avec "ceil", on va arrondir à l'entier supérieur le plus proche (si 8.87 = 9)
        $pages = ceil($paginator->count() / $limit);

        // On remplit le tableau
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;

        return $result;
    }

    // Pour permettre d'afficher les autres produits d'un producteur
    public function findOtherProductsByProducer(int $producerId, int $currentProductId, int $limit = 6)
    {
        return $this->createQueryBuilder('p')
            ->where('p.producer = :producerId')
            ->andWhere('p.id != :currentProductId')
            ->setParameter('producerId', $producerId)
            ->setParameter('currentProductId', $currentProductId)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    // Pour permettre de mettre d'autres produits de manière aléatoire dans la PopUp
    public function findOtherProducts2(int $limit = 4)
    {
        // Récupérer le nombre total de produits
        $totalProducts = $this->createQueryBuilder('p')
            ->select('COUNT(p.name)')
            ->getQuery()
            // On exécute la requête et retourne le résultat sous forme de valeur unique(nbr total de produit)
            ->getSingleScalarResult();

        // Déterminer un offset aléatoire
        // Donc si 10 produits mais limite de 4 ===>> le offset(ceux quon voir pas sera de 6)
        $offset = rand(0, max(0, $totalProducts - $limit));

        return $this->createQueryBuilder('p')
            ->setFirstResult($offset) // Définit l'offset aléatoire
            ->setMaxResults($limit) // Limite le nombre de résultats
            ->getQuery()
            ->getResult();
    }
}
