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

    public function add(UploadedFile $picture, ?string $folder = '', ?int $maxWidth = 300, ?int $maxHeight = 350)
    {
        // Récupérer le nom d'origine du fichier
        $originalName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

        // Récupérer les informations de l'image
        $pictureInfos = getimagesize($picture);

        // Vérifier si l'image est valide
        if ($pictureInfos === false || !in_array($pictureInfos['mime'], self::SUPPORTED_FORMATS)) {
            throw new Exception("Format d'image incorrect ou non supporté");
        }

        // Créer la ressource d'image selon le format
        $source = $this->createImageFromFile($picture, $pictureInfos['mime']);

        // Corriger l'orientation de l'image si nécessaire (EXIF)
        if ($pictureInfos['mime'] === 'image/jpeg') {
            $source = $this->correctImageOrientation($picture, $source);
        }

        // Obtenir les dimensions d'origine
        $width = $pictureInfos[0];
        $height = $pictureInfos[1];

        // Calculer les nouvelles dimensions en respectant le ratio
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);

        // Créer une nouvelle image redimensionnée
        $resizedPicture = imagecreatetruecolor($newWidth, $newHeight);

        // Préserver la transparence pour les PNG
        if ($pictureInfos['mime'] === 'image/png') {
            imagealphablending($resizedPicture, false);
            imagesavealpha($resizedPicture, true);
        }

        // Redimensionner l'image
        imagecopyresampled($resizedPicture, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Déterminer le chemin de stockage
        $path = $this->params->get('images_directory') . $folder;

        // Créer le dossier s'il n'existe pas
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Sauvegarder l'image en WebP
        $saved = imagewebp($resizedPicture, $path . '/' . $originalName, 95);

        // Appeler la méthode pour sauvegarder l'image en fonction du type de fichier
        $this->saveImage($resizedPicture, $path, $originalName, $pictureInfos['mime']);

        // Libérer la mémoire
        imagedestroy($source);
        imagedestroy($resizedPicture);

        // Vérifier si l'image a bien été enregistrée
        if (!$saved) {
            throw new Exception("Erreur lors de l'enregistrement de l'image");
        }

        // Retourner le nom de l'image
        return $originalName;
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

    private function saveImage($image, $path, $originalName, $mime)
    {
        switch ($mime) {
            case 'image/png':
                imagepng($image, $path . '/' . $originalName);
                break;
            case 'image/jpeg':
                imagejpeg($image, $path . '/' . $originalName, 95); // Ajustez la qualité JPEG ici
                break;
            case 'image/webp':
                imagewebp($image, $path . '/' . $originalName, 95); // Ajustez la qualité WebP ici
                break;
            default:
                throw new Exception("Format d'image non supporté");
        }
    }

    private function correctImageOrientation(UploadedFile $picture, $image)
    {
        // Lire les données EXIF (uniquement pour les fichiers JPEG)
        $exif = @exif_read_data($picture->getPathname());

        if ($exif && isset($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, 180, 0); // Rotation de 180°
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0); // Rotation de 90° dans le sens horaire
                    break;
                case 8:
                    $image = imagerotate($image, 90, 0); // Rotation de 90° dans le sens anti-horaire
                    break;
            }
        }

        return $image;
    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 300, ?int $height = 350)
    {
        if ($fichier !== 'default.webp') {
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;

            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $fichier;

            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}
