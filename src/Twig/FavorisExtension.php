<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FavorisExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('favoris_total_quantity', [$this, 'getTotalQuantity']),
        ];
    }

    public function getTotalQuantity(): int
    {
        // Récupérer la session à partir de la requête courante
        $session = $this->requestStack->getSession();

        // Récupérer les favoris (par défaut un tableau vide)
        $favoris = $session->get('favoris', []);

        // Retourner le nombre total de favoris
        return count($favoris);
    }
}
