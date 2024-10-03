<?php

namespace App\Service;

use Exception;

class GoogleService
{
    private string $googleApiKey;

    public function __construct(string $googleApiKey)
    {
        $this->googleApiKey = $googleApiKey;
    }

    // Fonction pour obtenir les coordonnées d'une adresse via l'API de Google Maps
    public function getCoordinatesFromAddress(string $address): array
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $this->googleApiKey;

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (isset($data['results'][0]['geometry']['location'])) {
            return [
                'lat' => $data['results'][0]['geometry']['location']['lat'],
                'lon' => $data['results'][0]['geometry']['location']['lng'],
            ];
        }

        throw new Exception("Impossible de géocoder l'adresse");
    }

    // Fonction pour calculer la distance entre deux points géographiques (en km)
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Rayon de la Terre en km

        // Conversion des coordonnées en radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Différences des coordonnées
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        // Formule de Haversine pour calculer la distance
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Retourner la distance
        return $earthRadius * $c;
    }

    // Fonction pour calculer les frais de livraison en fonction de la distance
    public function calculateDeliveryCostFromDistance(float $distance): float
    {
        if ($distance <= 5) {
            return 0; // Livraison gratuite pour moins de 5 km
        } elseif ($distance <= 10) {
            return 5; // Frais de livraison de 5€ pour 5-10 km
        } elseif ($distance <= 15) {
            return 10; // Frais de livraison de 10€ pour 10-15 km
        } else {
            return 0; // Livraison non disponible au-delà de 15 km
        }
    }
}
