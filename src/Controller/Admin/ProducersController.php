<?php

namespace App\Controller\Admin;

use App\Entity\Producer;
use App\Form\ProducerFormType;
use App\Repository\ProducerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin', name: 'admin_')]
class ProducersController extends AbstractController
{
    #[Route('/producteurs', name: 'producers')]
    function index(Producer $producer, ProducerRepository $producerRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $producer = $producerRepository->findAll([]);

        return $this->render('admin/producers/index.html.twig', [
            'producer' => $producer
        ]);
    }

    #[Route('/producteurs/ajout', name: 'producers_add')]
    function add(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $producer = new Producer();

        // On va créer le forumlaire
        $producerForm = $this->createForm(ProducerFormType::class, $producer);

        // On va traiter le formulaire
        $producerForm->handleRequest($request);

        // On va voir si le formulaire est soumis et valide
        if ($producerForm->isSubmitted() && $producerForm->isValid()) {

            $em->persist($producer);
            $em->flush();

            $this->addFlash('success', 'Producteur ajouté avec succès');
            return $this->redirectToRoute('admin_producers');
        }

        return $this->render('admin/producers/add.html.twig', [
            'producerForm' => $producerForm
        ]);
    }

    #[Route('/producteurs/edition/{id}', name: 'producers_edit')]
    function edit(Producer $producer, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $producer);

        // On crée le formulaire
        $producerForm = $this->createForm(ProducerFormType::class, $producer);

        // On traite le formulaire
        $producerForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($producerForm->isSubmitted() && $producerForm->isValid()) {

            $em->persist($producer);
            $em->flush();

            $this->addFlash('success', 'Producteur modifié avec succès');
            return $this->redirectToRoute('admin_producers');
        }

        return $this->render('admin\producers\edit.html.twig', [
            'producerForm' => $producerForm,
            'producer' => $producer
        ]);
    }

    #[Route('/producteurs/suppression/{id}', name: 'producers_delete')]
    function delete(Producer $producer, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Ajouter un token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $producer->getId(), $request->request->get('_token'))) {
            $em->remove($producer);
            $em->flush();

            $this->addFlash('success', 'Producteur supprimé avec succès');
        } else {
            $this->addFlash('danger', 'Échec de la suppression du producteur');
        }

        return $this->redirectToRoute('admin_producers');
    }
}
