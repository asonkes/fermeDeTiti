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

    #[Route('/details_des_commandes/edition/{orderId}/{productId}', name: 'ordersDetails_edit')]
    public function edit(int $orderId, int $productId, OrdersDetails $ordersDetails, OrdersDetailsRepository $ordersDetailsRepository, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $ordersDetails = $ordersDetailsRepository->findOneBy([
            'orders' => $orderId,
            'products' => $productId
        ]);

        // On va créer le formulaire
        $ordersDetailsForm = $this->createForm(OrdersDetailsFormType::class, $ordersDetails);

        // On traite le formulaire
        $ordersDetailsForm->handleRequest($request);

        // On va voir si le forulaire ets soumis et valide
        if ($ordersDetailsForm->isSubmitted() && $ordersDetailsForm->isValid()) {
            $em->persist($ordersDetails);
            $em->flush();

            $this->addFlash('success', 'Les Détails de la commande sont bien modifiés avec succès');

            return $this->redirectToRoute('admin_ordersDetails');
        }

        return $this->render('admin/ordersDetails/edit.html.twig', [
            'ordersDetailsForm' => $ordersDetailsForm,
            'ordersDetails' => $ordersDetails
        ]);
    }

    #[Route('/details_des_commandes/suppression/{orderId}/{productId}', name: 'ordersDetails_delete')]
    public function delete(int $orderId, int $productId, OrdersDetailsRepository $ordersDetailsRepository, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $ordersDetails = $ordersDetailsRepository->findOneBy([
            'orders' => $orderId,
            'products' => $productId
        ]);

        //Ajouter un token pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete_' . $orderId . '_' . $productId, $request->request->get('_token'))) {
            $em->remove($ordersDetails);
            $em->flush();

            $this->addFlash('success', 'Les détails de cette commande ont été supprimées avec succès');
        } else {
            $this->addFlash('danger', 'Échec de la suppression des détails de cette commande.');
        }

        return $this->redirectToRoute('admin_ordersDetails');
    }
}
