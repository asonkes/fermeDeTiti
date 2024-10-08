<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Form\OrdersDetailsFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrdersDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class OrdersDetailsController extends AbstractController
{
    #[Route('/details_des_commandes', name: 'ordersDetails')]
    public function index(OrdersDetails $ordersDetails, OrdersDetailsRepository $ordersDetailsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $ordersDetails = $ordersDetailsRepository->findAll([]);

        return $this->render('admin/ordersDetails/index.html.twig', [
            'ordersDetails' => $ordersDetails
        ]);
    }

    #[Route('/details_des_commandes/edition/{ordersId}/{productsId}', name: 'admin_ordersDetails_edit')]
    public function edit(int $ordersId, int $productsId, OrdersDetailsRepository $ordersDetailsRepository, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer les détails de commande
        $ordersDetails = $ordersDetailsRepository->findOneBy([
            'orders' => $ordersId,
            'products' => $productsId
        ]);

        // Traitement du formulaire
        $ordersDetailsForm = $this->createForm(OrdersDetailsFormType::class, $ordersDetails);
        $ordersDetailsForm->handleRequest($request);

        if ($ordersDetailsForm->isSubmitted() && $ordersDetailsForm->isValid()) {
            $em->persist($ordersDetails);
            $em->flush();

            $this->addFlash('success', 'Commande modifiée avec succès');

            return $this->redirectToRoute('admin_ordersDetails');
        }

        // Rendu de la vue
        return $this->render('admin/ordersDetails/edit.html.twig', [
            'ordersDetailsForm' => $ordersDetailsForm->createView(),
            'ordersDetails' => $ordersDetails
        ]);
    }

    #[Route('/details_des_commandes/suppression/{id}', name: 'ordersDetails_delete')]
    public function delete(Orders $orders, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Ajouter un token pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete' . $orders->getId(), $request->request->get('_token'))) {
            $em->remove($orders);
            $em->flush();

            $this->addFlash('success', 'Les détails de cette commande ont été supprimées avec succès');
        } else {
            $this->addFlash('danger', 'Échec de la suppression des détails de cette commande.');
        }

        return $this->redirectToRoute('admin_ordersDetails');
    }
}
