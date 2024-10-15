<?php

namespace App\Service;

use GuzzleHttp\Client;

class GeocodingService
{
    private Client $client;

    public function __construct()
    {
        // Initialiser le client Guzzle
        $this->client = new Client();
    }

    public function getCoordinates(string $address, string $zipcode, string $city): ?array
    {
        $formattedAddress = urlencode("$address, $zipcode, $city");
        $url = "https://nominatim.openstreetmap.org/search?format=json&q={$formattedAddress}";

        try {
            $response = $this->client->request('GET', $url, [
                'verify' => true, // Assurez-vous que cela est configuré correctement
                'headers' => [
                    'User-Agent' => 'MonApplication/1.0 (https://fermedewarelles.audrey-sonkes.be)'
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Vérifier si une adresse a été trouvée
            if (isset($data[0])) {
                return [
                    'latitude' => (float) $data[0]['lat'],
                    'longitude' => (float) $data[0]['lon'],
                ];
            }
        } catch (\Exception $e) {
            // Log l'erreur pour avoir plus d'informations
            error_log($e->getMessage('prob'));
        }

        return null;
    }
}
