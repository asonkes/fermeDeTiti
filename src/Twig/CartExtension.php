<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cart_total_quantity', [$this, 'getTotalQuantity']),
        ];
    }

    public function getTotalQuantity(): int
    {
        // Récupérer la session à partir de la requête courante
        $session = $this->requestStack->getSession();

        $panier = $session->get('panier', []);
        $totalQuantity = 0;

        foreach ($panier as $quantity) {
            $totalQuantity += $quantity;
        }

        return $totalQuantity;
    }
}
