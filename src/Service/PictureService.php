<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Exception;

class PictureService
{
    private $params;

    const SUPPORTED_FORMATS = ['image/png', 'image/jpeg', 'image/webp'];

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $maxWidth = 250, ?int $maxHeight = 350)
    {
        // Générer un nom unique pour l'image
        $fileName = md5(uniqid(rand(), true)) . '.webp';

        // Récupérer les informations de l'image
        $pictureInfos = getimagesize($picture);

        // Vérifier si l'image est valide
        if ($pictureInfos === false || !in_array($pictureInfos['mime'], self::SUPPORTED_FORMATS)) {
            throw new Exception("Format d'image incorrect ou non supporté");
        }

        // Créer la ressource d'image selon le format
        $source = $this->createImageFromFile($picture, $pictureInfos['mime']);

        // Obtenir les dimensions d'origine
        $width = $pictureInfos[0];
        $height = $pictureInfos[1];

        // Calculer les nouvelles dimensions en respectant le ratio
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);

        // Créer une nouvelle image redimensionnée
        $resizedPicture = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedPicture, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Déterminer le chemin de stockage
        $path = $this->params->get('images_directory') . $folder;

        // Créer le dossier s'il n'existe pas
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Sauvegarder l'image en WebP
        $saved = imagewebp($resizedPicture, $path . '/' . $fileName);

        // Libérer la mémoire
        imagedestroy($source);
        imagedestroy($resizedPicture);

        // Vérifier si l'image a bien été enregistrée
        if (!$saved) {
            throw new Exception("Erreur lors de l'enregistrement de l'image");
        }

        // Retourner le nom de l'image
        return $fileName;
    }

    private function createImageFromFile($picture, $mime)
    {
        switch ($mime) {
            case 'image/png':
                return imagecreatefrompng($picture);
            case 'image/jpeg':
                return imagecreatefromjpeg($picture);
            case 'image/webp':
                return imagecreatefromwebp($picture);
            default:
                throw new Exception("Format d'image non supporté");
        }
    }

    public function delete(string $fichier, ?string $folder = '')
    {
        // Ne pas supprimer l'image par défaut
        if ($fichier === 'default.webp') {
            return false;
        }

        $path = $this->params->get('images_directory') . $folder;

        // Déterminer le chemin de l'image originale
        $originalPath = $path . '/' . $fichier;

        // Initialiser le succès à faux
        $success = false;

        // Suppression de l'image originale
        if (file_exists($originalPath) && unlink($originalPath)) {
            $success = true;
        }

        return $success;
    }
}
