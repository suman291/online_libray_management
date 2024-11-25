<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NearestLibrariesController extends Controller
{
// This method is used to display the view with user coordinates
public function index()
{
$user = Auth::user();
$latitude = $user->lat;
$longitude = $user->long;

// You can use the showNearbyLibraries method to get nearby libraries
// or you can directly call the query in this method
return view('nearbylibraries.index', compact('latitude', 'longitude'));
}

// Method to show nearby libraries based on the user's location
public function showNearbyLibraries(Request $request)
{
// Get user's current latitude and longitude
$userLat = $request->input('lat');
$userLong = $request->input('long');
$radius = 10; // Radius in kilometers

// Query to fetch nearby libraries within the specified radius (10 km)
$libraries = DB::table('libraies')
->select(
'id',
'name',
'lat',
'long',
DB::raw("ST_Distance_Sphere(POINT(`lat`, `long`), POINT($userLat, $userLong)) AS distance")
)
->whereRaw("ST_Distance_Sphere(POINT(`lat`, `long`), POINT($userLat, $userLong)) <= ?", [$radius * 1000]) // Convert km to meters
    ->orderBy('distance', 'asc') // Order by distance (ascending)
    ->get();

    // Return the libraries and user location to the view
    return response()->json(['libraries' => $libraries, 'userLat' => $userLat, 'userLong' => $userLong, 'radius' => $radius]);
    }
    }