<?php

namespace App\Controller;

use App\Repository\ProducerRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/article', name: 'article_')]
class ArticleController extends AbstractController
{
    #[Route('/{slug}', name: 'index')]
    public function index(string $slug, ProductsRepository $productsRepository, ProducerRepository $producerRepository): Response
    {
        $product = $productsRepository->findOneBy(['slug' => $slug]);

        $producer = $product->getProducer();

        return $this->render('article/index.html.twig', [
            'product' => $product,
            'producer' => $producer
        ]);
    }
}
