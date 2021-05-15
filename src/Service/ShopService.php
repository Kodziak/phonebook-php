<?php


namespace App\Service;

use GuzzleHttp\Client;

class ShopService
{
    public function getLongLat(string $street, string $postCode, string $city)
    {
        $client = new Client();

        $str = urlencode($street);
        $pc = urlencode($postCode);
        $ct = urlencode($city);

        $response = $client->request('GET', "https://nominatim.openstreetmap.org/search.php?street=$str&postalcode=$pc&city=$ct&polygon_geojson=1&format=jsonv2");
        $body = $response->getBody();
        $data = json_decode($body);

        return reset($data);
    }

    public function getAddress(string $lat, string $lon)
    {
        $client = new Client();

        $response = $client->request('GET', "https://nominatim.openstreetmap.org/reverse?lat=$lat&lon=$lon&polygon_geojson=1&format=jsonv2");
        $body = json_decode($response->getBody(), true);

        return $body['address'];
    }
}