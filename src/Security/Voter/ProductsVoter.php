<?php

namespace App\Security\Voter;

use App\Entity\Products;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class ProductsVoter extends Voter
{
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // Renvoie booléen car c'est true ou false si personne a le droit d'avoir accès à telle ou telle donnée
    protected function supports(string $attribute, $product): bool
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$product instanceof Products) {
            return true;
        }
    }

    // Renvoie booléen car c'est true ou false si personne a le droit d'avoir accès à telle ou telle donnée
    protected function voteOnAttribute($attribute, $product, TokenInterface $token): bool
    {
        // On récupère l'utilisateur à partir du token
        $user = $token->getUser();

        // Si utilisateur n'est pas connecté, je return "false"
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On vérifie si l'utilisateur est admin, je return "true"
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Si l'utilisateur est connecté mais n'est pas admin
        // On vérifie les permissions
        switch ($attribute) {
            case self::EDIT:
                // On vérifie si l'utilisateur peut éditer
                return $this->canEdit();
                break;
            case self::DELETE:
                // On vérifie si l'utilisateur peut supprimer
                return $this->canDelete();
                break;
        }
    }

    private function canEdit()
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    private function canDelete()
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }
}
