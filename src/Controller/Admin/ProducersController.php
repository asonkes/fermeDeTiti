<?php

namespace App\Controller\Admin;

use App\Entity\Producer;
use App\Repository\ProducerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin', name: 'admin_')]
class ProducersController extends AbstractController
{
    #[Route('/producteurs', name: 'producers')]
    function index(Producer $producer, ProducerRepository $producerRepository): Response
    {
        $producer = $producerRepository->findAll([]);

        return $this->render('admin/producers/index.html.twig', [
            'producer' => $producer
        ]);
    }

    #[Route('/producteurs/ajout', name: 'producers_add')]
    function add(): Response
    {

        return $this->render('admin/producers/add.html.twig', []);
    }
}
