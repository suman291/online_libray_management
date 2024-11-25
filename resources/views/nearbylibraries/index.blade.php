@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div id="map" class="mt-1"></div>
@endsection
@section('css')
   <link rel="stylesheet" href="{{ asset('vendor/leafLet/leaflet.css')}}">
   <style>
        #map {
            height: 500px; 
        }
    </style>
@stop

@section('js')
<script src="{{asset('vendor/leafLet/leaflet.js')}}"></script>
<script>
   var latitude = 0;
var longitude = 0;

// Check if geolocation is supported
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
        latitude = position.coords.latitude; 
        longitude = position.coords.longitude;
        
        // Initialize the map after getting the user's location
        var map = L.map('map').setView([latitude, longitude], 13);

        // Add tile layer
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Fetch nearby libraries
        $.ajax({
            url: "{{ route('nearbylibraries.show') }}", // The endpoint
            type: 'POST', // HTTP method
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}" // Include CSRF token in headers
            },
            data: JSON.stringify({ // Data to send in the request body
                lat: latitude,
                long: longitude
            }),
            contentType: 'application/json', // Tell the server we're sending JSON
            success: function (data) { // Handle success response
                const libraries = data.libraries;

                // Add library markers to the map
                libraries.forEach(function (library) {
                    L.marker([library.lat, library.long])
                        .addTo(map)
                        .bindPopup('<b>' + library.name + '</b><br>Latitude: ' + library.lat + '<br>Longitude: ' + library.long + '<br>Distance: ' + (library.distance / 1000).toFixed(2) + ' km')
                        .openPopup();
                });
            },
            error: function (xhr, status, error) { // Handle error response
                console.error('Error fetching nearby libraries:', xhr.responseJSON.message);
            }
        });
    }, (error) => {
        console.error('Error obtaining location:', error.message);
    });
} else {
    console.error('Geolocation is not supported by this browser.');
}


</script>
@stop