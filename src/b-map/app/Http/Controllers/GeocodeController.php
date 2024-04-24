<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    public function geocode(Request $request)
    {
        $apiKey = config('services.google_geocoding.api_key');
        $address = $request->address;
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'address' => $address,
            'key' => $apiKey,
        ]);

        if ($response->successful()) {
            $location = $response->json()['results'][0]['geometry']['location'];
            return response()->json([
                'latitude' => $location['lat'],
                'longitude' => $location['lng'],
            ]);
        }

        return response()->json(['error' => 'Unable to geocode address'], 422);
    }
}
