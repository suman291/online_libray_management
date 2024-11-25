<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getAddress(Request $request)
    {
        $address = $request->input('address');
        function getCoordinatesWithCurl($address)
        {
            // URL encode the address to ensure it can be passed as a query parameter
            $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address);

            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: MyGeocoder/1.0 (devoloperssuman22@gmail.com)"]);

            // Execute the cURL request
            $response = curl_exec($ch);

            // Close cURL session
            curl_close($ch);

            // Decode the JSON response
            $data = json_decode($response, true);

            // If data is returned, extract latitude and longitude
            if (!empty($data) && isset($data[0])) {
                return [
                    'lat' => $data[0]['lat'],
                    'lon' => $data[0]['lon']
                ];
            }

            // Return null if no data is found
            return null;
        }
        
        $coordinates = getCoordinatesWithCurl($address);
       
        // Display the coordinates or an error message
        if ($coordinates) {
            $data = [
                "status" => "success",
                "data" => [
                    "Latitude" => $coordinates['lat'],
                    "Longitude" => $coordinates['lon']
                ]
            ];
        } else {
            $data = [
                "status" => "error",
                "message" => "Address not found or invalid."
            ];
        }

        echo json_encode($data);

    }
}
